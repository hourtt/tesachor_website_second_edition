<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * @property int $id
 * @property string $name
 * @property string $name_in_khmer
 * @property string $slug
 * @property string|null $thumbnail
 * @property-read string|null $thumbnail_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Destination> $destinations
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Province extends Model
{
    //
    protected $fillable = [
        'name',
        'name_in_khmer',
        'slug',
        'thumbnail',
    ];

    // * set the name as plural because one province has many places to visit
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (!$this->thumbnail) {
                    return null;
                }
                if (
                    str_starts_with($this->thumbnail, 'http://') ||
                    str_starts_with($this->thumbnail, 'https://')
                ) {
                    return $this->thumbnail;
                }
                /** @var FilesystemAdapter $disk */
                $disk = Storage::disk('supabase_province');
                return $disk->url($this->thumbnail);
            }
        );
    }
}
