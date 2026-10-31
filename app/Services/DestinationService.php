<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Province;
use Aws\CommandPool;
use Aws\S3\S3Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationService
{
    protected string $disk = 'supabase_destination';

    public function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'destination';
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
        return Destination::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

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

    public function getFilteredDestinations(array $filters = [], int $limit = 12): Collection
    {
        $query = Destination::query()->select([
            'id',
            'province_id',
            'category_id',
            'place_name',
            'slug',
            'short_description',
            'thumbnail',
            'rating',
            'is_recommended',
            'created_at',
        ])->with([
            'province:id,name,name_in_khmer,slug',
            'category:id,name,slug',
        ]);
        $search = isset($filters['search']) ? trim((string) $filters['search']) : '';
        $category = $filters['category'] ?? $filters['category_id'] ?? null;

        if ($search !== '') {
            // Pre-fetch matching province IDs (25 rows = <0.5ms) instead of slow correlated EXISTS subquery
            $matchedProvinceIds = Province::query()
                ->where('name', 'like', "%{$search}%")
                ->orWhere('name_in_khmer', 'like', "%{$search}%")
                ->pluck('id');

            $query->where(function ($q) use ($search, $matchedProvinceIds) {
                $q->where('place_name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");

                if ($matchedProvinceIds->isNotEmpty()) {
                    $q->orWhereIn('province_id', $matchedProvinceIds);
                }
            });
        }

        if (! empty($category)) {
            if (is_numeric($category)) {
                $query->where('category_id', (int) $category);
            } else {
                $categoryId = Category::query()
                    ->where('slug', $category)
                    ->orWhere('name', $category)
                    ->value('id');

                // If category doesn't exist, force an empty result immediately
                $query->where('category_id', $categoryId ?? 0);
            }
        }

        // If neither search nor category is filtered, default to recommended
        if ($search === '' && empty($category)) {
            $query->where('is_recommended', true);
        }

        $safeLimit = min($limit ?? 12, 50);

        return $query->latest('id')->take($safeLimit)->get();
    }

    public function createDestination(array $data, ?UploadedFile $thumbnail = null, ?array $galleryImages = null): Destination
    {
        @set_time_limit(180);

        // Remove file objects from $data before DB create
        unset($data['thumbnail'], $data['gallery_images']);

        if (isset($data['highlights']) && is_array($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn ($value) => ! is_null($value) && trim((string) $value) !== ''));
        }

        $data['slug'] = $this->uniqueSlug($data['place_name'] ?? '');

        return DB::transaction(function () use ($data, $thumbnail, $galleryImages) {
            try {
                $destination = Destination::create($data);
            } catch (UniqueConstraintViolationException $e) {
                $data['slug'] = $this->uniqueSlug($data['place_name'] ?? '');
                $destination = Destination::create($data);
            }
            $destinationId = (string) $destination->getKey();

            $uploadedFiles = [];
            try {
                if ($thumbnail) {
                    $thumbPaths = $this->uploadFilesConcurrently([$thumbnail], "thumbnails/{$destinationId}");
                    if (! empty($thumbPaths)) {
                        $destination->thumbnail = $thumbPaths[0];
                        $uploadedFiles[] = $thumbPaths[0];
                    }
                }

                if ($galleryImages) {
                    $galleryPaths = $this->uploadFilesConcurrently($galleryImages, "gallery/{$destinationId}");
                    $destination->gallery_images = $galleryPaths;
                    $uploadedFiles = array_merge($uploadedFiles, $galleryPaths);
                }

                if ($thumbnail || $galleryImages) {
                    $destination->save();
                }

                return $destination;
            } catch (\Throwable $e) {
                // If anything fails, clean up any uploaded files from cloud storage
                $disk = Storage::disk($this->disk);
                foreach ($uploadedFiles as $file) {
                    try {
                        $disk->delete($file);
                    } catch (\Throwable $cleanupException) {
                        // ignore cleanup errors
                    }
                }
                $destination->delete();
                throw $e;
            }
        });
    }

    public function updateDestination(Destination $destination, array $data, ?UploadedFile $thumbnail = null, ?array $galleryImages = null): Destination
    {
        @set_time_limit(180);

        $disk = Storage::disk($this->disk);
        $destinationId = (string) $destination->getKey();

        if (isset($data['highlights']) && is_array($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn ($value) => ! is_null($value) && trim((string) $value) !== ''));
        }

        if (isset($data['place_name']) && $destination->place_name !== $data['place_name']) {
            $data['slug'] = $this->uniqueSlug($data['place_name'], $destination->id);
        }

        return DB::transaction(function () use ($destination, $data, $thumbnail, $galleryImages, $disk, $destinationId) {
            $oldThumbnail = $destination->thumbnail;
            $oldGallery = $destination->gallery_images;

            $uploadedFiles = [];
            try {
                if ($thumbnail) {
                    $thumbPaths = $this->uploadFilesConcurrently([$thumbnail], "thumbnails/{$destinationId}");
                    if (! empty($thumbPaths)) {
                        $data['thumbnail'] = $thumbPaths[0];
                        $uploadedFiles[] = $thumbPaths[0];
                    }
                } else {
                    unset($data['thumbnail']);
                }

                if ($galleryImages) {
                    $galleryPaths = $this->uploadFilesConcurrently($galleryImages, "gallery/{$destinationId}");
                    $data['gallery_images'] = $galleryPaths;
                    $uploadedFiles = array_merge($uploadedFiles, $galleryPaths);
                } else {
                    unset($data['gallery_images']);
                }

                $destination->update($data);

                // Clean up old files only after successful database update
                if ($thumbnail && $oldThumbnail && ! str_starts_with($oldThumbnail, 'http://') && ! str_starts_with($oldThumbnail, 'https://')) {
                    $disk->delete($oldThumbnail);
                }

                if ($galleryImages && is_array($oldGallery)) {
                    $this->deleteFilesSafely($disk, $oldGallery);
                }

                return $destination;
            } catch (\Throwable $e) {
                // If anything fails during update, delete newly uploaded files
                foreach ($uploadedFiles as $file) {
                    try {
                        $disk->delete($file);
                    } catch (\Throwable $cleanupException) {
                        // ignore
                    }
                }
                throw $e;
            }
        });
    }

    public function deleteDestination(Destination $destination): bool
    {
        $disk = Storage::disk($this->disk);
        if ($destination->thumbnail && ! str_starts_with($destination->thumbnail, 'http://') && ! str_starts_with($destination->thumbnail, 'https://')) {
            $disk->delete($destination->thumbnail);
        }

        if ($destination->gallery_images && is_array($destination->gallery_images)) {
            $this->deleteFilesSafely($disk, $destination->gallery_images);
        }

        return (bool) $destination->delete();
    }

    /**
     * Upload an array of UploadedFiles concurrently or sequentially.
     * Automatically optimizes large images to reduce bandwidth and maximize transfer speed.
     *
     * @param  array<UploadedFile>  $files
     * @return array<string> File paths stored on disk
     */
    protected function uploadFilesConcurrently(array $files, string $directory): array
    {
        if (empty($files)) {
            return [];
        }

        $disk = Storage::disk($this->disk);
        $tempFilesToClean = [];

        try {
            $preparedUploads = [];
            foreach ($files as $file) {
                if (! ($file instanceof UploadedFile) || ! $file->isValid()) {
                    continue;
                }

                $hash = Str::random(40);
                $ext = strtolower($file->guessExtension() ?? $file->getClientOriginalExtension() ?? 'jpg');
                $targetPath = "{$directory}/{$hash}.{$ext}";

                $localSource = $this->optimizeImageForUpload($file, $tempFilesToClean);
                $mime = $file->getMimeType() ?? 'image/jpeg';

                $preparedUploads[] = [
                    'path' => $targetPath,
                    'source' => $localSource,
                    'mime' => $mime,
                    'file' => $file,
                ];
            }

            if (empty($preparedUploads)) {
                return [];
            }

            // High-speed parallel upload if Aws S3 Client is available
            if (method_exists($disk, 'getClient') && class_exists(CommandPool::class)) {
                try {
                    $client = $disk->getClient();
                    if ($client instanceof S3Client) {
                        $bucket = config("filesystems.disks.{$this->disk}.bucket");
                        $commands = [];
                        $storedPaths = [];

                        foreach ($preparedUploads as $upload) {
                            $storedPaths[] = $upload['path'];
                            $commands[] = $client->getCommand('PutObject', [
                                'Bucket' => $bucket,
                                'Key' => $upload['path'],
                                'SourceFile' => $upload['source'],
                                'ContentType' => $upload['mime'],
                            ]);
                        }

                        $pool = new CommandPool($client, $commands, [
                            'concurrency' => 5,
                        ]);

                        $pool->promise()->wait();

                        return $storedPaths;
                    }
                } catch (\Throwable $e) {
                    Log::warning('Concurrent S3 upload fallback to standard: '.$e->getMessage());
                }
            }

            // Fallback for local disk, testing fakes, or if command pool failed
            $storedPaths = [];
            foreach ($preparedUploads as $upload) {
                if (file_exists($upload['source']) && $upload['source'] !== $upload['file']->getRealPath()) {
                    $stream = fopen($upload['source'], 'r');
                    $disk->put($upload['path'], $stream, ['mimetype' => $upload['mime']]);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    $storedPaths[] = $upload['path'];
                } else {
                    $storedPaths[] = $disk->putFile($directory, $upload['file']);
                }
            }

            return $storedPaths;
        } finally {
            // Clean up temporary resized files
            foreach ($tempFilesToClean as $tempFile) {
                if (file_exists($tempFile)) {
                    @unlink($tempFile);
                }
            }
        }
    }

    /**
     * Resizes/optimizes large uploaded photos to max 1920px to reduce payload size by 80-95%.
     * Returns path to temporary optimized image or original file path.
     */
    protected function optimizeImageForUpload(UploadedFile $file, array &$tempFilesToClean, int $maxDim = 1920, int $quality = 82): string
    {
        $realPath = $file->getRealPath();
        if (! $realPath || ! file_exists($realPath) || ! extension_loaded('gd')) {
            return $realPath;
        }

        $imageInfo = @getimagesize($realPath);
        if (! $imageInfo) {
            return $realPath;
        }

        [$origWidth, $origHeight, $type] = $imageInfo;

        // If image is already reasonably sized (< 1920px on both dimensions and < 1MB), no resize needed
        if ($origWidth <= $maxDim && $origHeight <= $maxDim && $file->getSize() <= 1048576) {
            return $realPath;
        }

        $src = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($realPath);
                break;
            case IMAGETYPE_PNG:
                $src = @imagecreatefrompng($realPath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $src = @imagecreatefromwebp($realPath);
                }
                break;
            default:
                return $realPath;
        }

        if (! $src) {
            return $realPath;
        }

        if ($origWidth > $origHeight) {
            $newWidth = min($origWidth, $maxDim);
            $newHeight = (int) round(($origHeight / $origWidth) * $newWidth);
        } else {
            $newHeight = min($origHeight, $maxDim);
            $newWidth = (int) round(($origWidth / $origHeight) * $newHeight);
        }

        $dest = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and WEBP
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            $transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
            imagefilledrectangle($dest, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($dest, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        $tmpFile = tempnam(sys_get_temp_dir(), 'dest_opt_');
        $saved = false;

        if ($type === IMAGETYPE_PNG) {
            $saved = imagepng($dest, $tmpFile, 7);
        } elseif ($type === IMAGETYPE_WEBP && function_exists('imagewebp')) {
            $saved = imagewebp($dest, $tmpFile, $quality);
        } else {
            $saved = imagejpeg($dest, $tmpFile, $quality);
        }

        imagedestroy($src);
        imagedestroy($dest);

        if ($saved && file_exists($tmpFile) && filesize($tmpFile) > 0) {
            $tempFilesToClean[] = $tmpFile;

            return $tmpFile;
        }

        if (file_exists($tmpFile)) {
            @unlink($tmpFile);
        }

        return $realPath;
    }

    /**
     * Batch delete files from disk if S3 client is available, otherwise loop.
     */
    protected function deleteFilesSafely($disk, array $files): void
    {
        $keysToDelete = [];
        foreach ($files as $file) {
            if (is_string($file) && ! str_starts_with($file, 'http://') && ! str_starts_with($file, 'https://')) {
                $keysToDelete[] = $file;
            }
        }

        if (empty($keysToDelete)) {
            return;
        }

        if (method_exists($disk, 'getClient')) {
            try {
                $client = $disk->getClient();
                if ($client instanceof S3Client) {
                    $bucket = config("filesystems.disks.{$this->disk}.bucket");
                    $client->deleteObjects([
                        'Bucket' => $bucket,
                        'Delete' => [
                            'Objects' => array_map(fn ($k) => ['Key' => $k], $keysToDelete),
                        ],
                    ]);

                    return;
                }
            } catch (\Throwable $e) {
                Log::warning('Batch S3 delete fallback to standard: '.$e->getMessage());
            }
        }

        foreach ($keysToDelete as $key) {
            $disk->delete($key);
        }
    }
}
