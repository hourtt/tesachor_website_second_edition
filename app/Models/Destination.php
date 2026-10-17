<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
