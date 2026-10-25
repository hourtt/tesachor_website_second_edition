<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * @property int $id
 * @property int $province_id
 * @property string $place_name
 * @property string $slug
 * @property string $short_description
 * @property string|null $thumbnail
 * @property bool $is_recommended
 * @property float $rating
 * @property string|null $place_description
 * @property string|null $place_history
 * @property string|null $place_information
 * @property array<string>|null $highlights
 * @property array<string>|null $gallery_images
 * @property-read string|null $thumbnail_url
 * @property-read array<string> $gallery_urls
 * @property-read \App\Models\Province|null $province
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Destination extends Model
{
    //

    protected $fillable = [
        'province_id',
        'place_name',
        'slug',
        'short_description',
        'thumbnail',
        'is_recommended',
        'rating',
        'place_description',
        'place_history',
        'place_information',
        'highlights',
        'gallery_images',
    ];

    // * $casts automatically converts model attributes to specific PHP data types
    protected $casts = [
        'highlights' => 'array',
        'gallery_images' => 'array',
        'is_recommended' => 'boolean',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (!$this->thumbnail) {
                    return null;
                }
                if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                    return $this->thumbnail;
                }
                /** @var FilesystemAdapter $disk */
                $disk = Storage::disk('supabase_destination');
                return $disk->url($this->thumbnail);
            }
        );
    }

    public function galleryUrls(): Attribute
    {
        return Attribute::make(
            get: function (): array {
                if (!$this->gallery_images || !is_array($this->gallery_images)) {
                    return [];
                }
                /** @var FilesystemAdapter $disk */
                $disk = Storage::disk('supabase_destination');
                return array_map(function ($path) use ($disk) {
                    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                        return $path;
                    }
                    return $disk->url($path);
                }, $this->gallery_images);
            }
        );
    }
}
