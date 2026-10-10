<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProvinceService
{
    public function getProvincesPaginated(int $perPage = 12)
    {
        return Province::orderBy('name', 'asc')->paginate($perPage);
    }

    public function getProvinceByIdOrSlug(string $identifier)
    {
        return Province::with('destinations')
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
    }

    public function createProvince(array $data, ?UploadedFile $thumbnail = null)
    {
        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail->store('province_thumbnails', 'public');
        }

        $data['slug'] = Str::slug($data['name']);

        return Province::create($data);
    }

    public function updateProvince(Province $province, array $data, ?UploadedFile $thumbnail = null)
    {
        if ($thumbnail) {
            if ($province->thumbnail) {
                Storage::disk('public')->delete($province->thumbnail);
            }
            $data['thumbnail'] = $thumbnail->store('province_thumbnails', 'public');
        }

        if ($province->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
        }

        $province->update($data);
        return $province;
    }

    public function deleteProvince(Province $province)
    {
        if ($province->destinations()->count() > 0) {
            return false;
        }

        if ($province->thumbnail) {
            Storage::disk('public')->delete($province->thumbnail);
        }

        return $province->delete();
    }
}
