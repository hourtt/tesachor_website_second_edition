<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProvinceService
{
    protected string $disk = 'supabase_province';

    public function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'province';
        }

        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Province::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    public function createProvince(array $data, ?UploadedFile $thumbnail = null): Province
    {
        $slug = $this->uniqueSlug($data['name'] ?? '');

        try {
            $province = Province::create([
                'name' => $data['name'],
                'name_in_khmer' => $data['name_in_khmer'] ?? null,
                'slug' => $slug,
                'thumbnail' => null,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            $slug = $this->uniqueSlug($data['name'] ?? '');
            $province = Province::create([
                'name' => $data['name'],
                'name_in_khmer' => $data['name_in_khmer'] ?? null,
                'slug' => $slug,
                'thumbnail' => null,
            ]);
        }

        if ($thumbnail) {
            try {
                $province->update([
                    'thumbnail' => Storage::disk($this->disk)
                        ->putFile("thumbnails/{$province->id}", $thumbnail),
                ]);
            } catch (Throwable $e) {
                $province->delete();   // don't leave a half-created record
                throw $e;
            }
        }

        return $province;
    }

    public function getProvincesPaginated(int $perPage = 8)
    {
        return Province::with('destinations')->orderBy('name', 'asc')->paginate($perPage);
    }

    public function getProvinceByIdOrSlug(string $identifier): Province
    {
        return Province::with('destinations')
            ->where(function ($query) use ($identifier) {
                if (is_numeric($identifier)) {
                    $query->where('id', (int) $identifier)
                        ->orWhere('slug', $identifier);
                } else {
                    $query->where('slug', $identifier);
                }
            })
            ->firstOrFail();
    }

    public function updateProvince(Province $province, array $data, ?UploadedFile $thumbnail = null): Province
    {
        $disk = Storage::disk($this->disk);
        $provinceId = (string) $province->getKey();

        if ($thumbnail) {
            if ($province->thumbnail && ! str_starts_with($province->thumbnail, 'http://') && ! str_starts_with($province->thumbnail, 'https://')) {
                $disk->delete($province->thumbnail);
            }
            $data['thumbnail'] = $disk->putFile("thumbnails/{$provinceId}", $thumbnail);
        } else {
            // Prevent wiping existing thumbnail when no new file is uploaded
            unset($data['thumbnail']);
        }

        if (isset($data['name']) && $province->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $province->id);
        }

        try {
            $province->update($data);
        } catch (UniqueConstraintViolationException $e) {
            if (isset($data['name'])) {
                $data['slug'] = $this->uniqueSlug($data['name'], $province->id);
                $province->update($data);
            } else {
                throw $e;
            }
        }

        return $province;
    }

    public function deleteProvince(Province $province): bool
    {
        if ($province->destinations()->count() > 0) {
            return false;
        }

        // Delete the thumbnail if it exists
        if ($province->thumbnail && ! str_starts_with($province->thumbnail, 'http://') && ! str_starts_with($province->thumbnail, 'https://')) {
            Storage::disk($this->disk)->delete($province->thumbnail);
        }

        return (bool) $province->delete();
    }
}
