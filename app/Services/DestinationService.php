<?php

namespace App\Services;

use App\Models\Destination;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationService
{
    protected string $disk = 'supabase_destination';

    public function getDestinationByIdOrSlug(string $identifier): Destination
    {
        return Destination::with(['province', 'category'])
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

    public function getRecommendedDestinations(int $limit = 6)
    {
        return Destination::where('is_recommended', true)
            ->with(['province', 'category'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function createDestination(array $data, ?UploadedFile $thumbnail = null, ?array $galleryImages = null): Destination
    {
        // Remove file objects from $data before initial DB create
        unset($data['thumbnail'], $data['gallery_images']);

        if (isset($data['highlights']) && is_array($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn ($value) => !is_null($value) && trim((string) $value) !== ''));
        }

        $data['slug'] = Str::slug($data['place_name']);

        $destination = Destination::create($data);
        $disk = Storage::disk($this->disk);
        $destinationId = (string) $destination->getKey();

        if ($thumbnail) {
            $destination->thumbnail = $disk->putFile("thumbnails/{$destinationId}", $thumbnail);
        }

        if ($galleryImages) {
            $galleryPaths = [];
            foreach ($galleryImages as $file) {
                $galleryPaths[] = $disk->putFile("gallery/{$destinationId}", $file);
            }
            $destination->gallery_images = $galleryPaths;
        }

        if ($thumbnail || $galleryImages) {
            $destination->save();
        }

        return $destination;
    }

    public function updateDestination(Destination $destination, array $data, ?UploadedFile $thumbnail = null, ?array $galleryImages = null): Destination
    {
        $disk = Storage::disk($this->disk);
        $destinationId = (string) $destination->getKey();

        if ($thumbnail) {
            if ($destination->thumbnail && !str_starts_with($destination->thumbnail, 'http://') && !str_starts_with($destination->thumbnail, 'https://')) {
                $disk->delete($destination->thumbnail);
            }
            $data['thumbnail'] = $disk->putFile("thumbnails/{$destinationId}", $thumbnail);
        } else {
            // Prevent wiping existing thumbnail when no new file is uploaded
            unset($data['thumbnail']);
        }

        // Gallery images update & cleanup
        if ($galleryImages) {
            if ($destination->gallery_images && is_array($destination->gallery_images)) {
                foreach ($destination->gallery_images as $oldImg) {
                    if (!str_starts_with($oldImg, 'http://') && !str_starts_with($oldImg, 'https://')) {
                        $disk->delete($oldImg);
                    }
                }
            }
            $galleryPaths = [];
            foreach ($galleryImages as $file) {
                $galleryPaths[] = $disk->putFile("gallery/{$destinationId}", $file);
            }
            $data['gallery_images'] = $galleryPaths;
        } else {
            // Prevent wiping existing gallery images when no new files are uploaded
            unset($data['gallery_images']);
        }

        if (isset($data['highlights']) && is_array($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn ($value) => !is_null($value) && trim((string) $value) !== ''));
        }

        if (isset($data['place_name']) && $destination->place_name !== $data['place_name']) {
            $data['slug'] = Str::slug($data['place_name']);
        }

        $destination->update($data);

        return $destination;
    }

    public function deleteDestination(Destination $destination): bool
    {
        $disk = Storage::disk($this->disk);
        if ($destination->thumbnail && !str_starts_with($destination->thumbnail, 'http://') && !str_starts_with($destination->thumbnail, 'https://')) {
            $disk->delete($destination->thumbnail);
        }

        if ($destination->gallery_images && is_array($destination->gallery_images)) {
            foreach ($destination->gallery_images as $img) {
                if (!str_starts_with($img, 'http://') && !str_starts_with($img, 'https://')) {
                    $disk->delete($img);
                }
            }
        }

        return (bool) $destination->delete();
    }
}
