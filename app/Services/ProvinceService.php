<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProvinceService
{

    protected string $disk = 'supabase_province';

    public function createProvince(array $data, ?UploadedFile $thumbnail = null): Province
    {
        $province = Province::create([
            'name' => $data['name'],
            'name_in_khmer' => $data['name_in_khmer'] ?? null,
            'slug' => Str::slug($data['name']),
            'thumbnail' => null,
        ]);

        if ($thumbnail) {
            $provinceId = (string) $province->getKey();
            $disk = Storage::disk($this->disk);
            // image file path: thumbnails/{province_id}/randomhash.jpg
            $province->thumbnail = $disk->putFile("thumbnails/{$provinceId}", $thumbnail);
            $province->save();
        }

        return $province;
    }

    public function getProvincesPaginated(int $perPage = 8)
    {
        return Province::orderBy('name', 'asc')->paginate($perPage);
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
            if ($province->thumbnail && !str_starts_with($province->thumbnail, 'http://') && !str_starts_with($province->thumbnail, 'https://')) {
                $disk->delete($province->thumbnail);
            }
            $data['thumbnail'] = $disk->putFile("thumbnails/{$provinceId}", $thumbnail);
        } else {
            // Prevent wiping existing thumbnail when no new file is uploaded
            unset($data['thumbnail']);
        }

        if (isset($data['name']) && $province->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
        }

        $province->update($data);

        return $province;
    }

    public function deleteProvince(Province $province): bool
    {
        if ($province->destinations()->count() > 0) {
            return false;
        }
        // Delete the thumbnail if it exists
        if ($province->thumbnail && !str_starts_with($province->thumbnail, 'http://') && !str_starts_with($province->thumbnail, 'https://')) {
            Storage::disk($this->disk)->delete($province->thumbnail);
        }

        return (bool) $province->delete();
    }
}
