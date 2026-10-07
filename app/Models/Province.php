<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $fillable = [
        'name',
        'name_in_khmer',
        'slug',
        'thumbnail',
    ];

    //* set the name as plural because one province has many places to visit
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
