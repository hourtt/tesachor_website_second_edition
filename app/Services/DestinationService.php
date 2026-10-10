<?php

namespace App\Services;

use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DestinationService
{
    public function getDestinationByIdOrSlug(string $identifier)
    {
        return Destination::with('province')
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
    }

    public function getRecommendedDestinations(int $limit = 6)
    {
        return Destination::where('is_recommended', true)
            ->with('province')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function createDestination(array $data, ?UploadedFile $thumbnail = null, ?array $galleryImages = null)
    {
        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail->store('destination_thumbnails', 'public');
        }

        if ($galleryImages) {
            $galleryPaths = [];
            foreach ($galleryImages as $file) {
                $galleryPaths[] = $file->store('destination_gallery', 'public');
            }
            $data['gallery_images'] = $galleryPaths;
        }

        if (! empty($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn($value) => ! is_null($value) && $value !== ''));
        }

        $data['slug'] = Str::slug($data['place_name']);

        return Destination::create($data);
    }

    public function updateDestination(Destination $destination, array $data, ?UploadedFile $thumbnail = null, ?array $galleryImages = null)
    {
        if ($thumbnail) {
            if ($destination->thumbnail) {
                Storage::disk('public')->delete($destination->thumbnail);
            }
            $data['thumbnail'] = $thumbnail->store('destination_thumbnails', 'public');
        }

        if ($galleryImages) {
            if ($destination->gallery_images) {
                foreach ($destination->gallery_images as $oldImg) {
                    if (Storage::disk('public')->exists($oldImg)) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
            }
            $galleryPaths = [];
            foreach ($galleryImages as $file) {
                $galleryPaths[] = $file->store('destination_gallery', 'public');
            }
            $data['gallery_images'] = $galleryPaths;
        }

        if (isset($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn($value) => ! is_null($value) && $value !== ''));
        }

        if ($destination->place_name !== $data['place_name']) {
            $data['slug'] = Str::slug($data['place_name']);
        }

        $destination->update($data);
        return $destination;
    }

    public function deleteDestination(Destination $destination)
    {
        if ($destination->thumbnail) {
            Storage::disk('public')->delete($destination->thumbnail);
        }

        if ($destination->gallery_images) {
            foreach ($destination->gallery_images as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }

        return $destination->delete();
    }
}
