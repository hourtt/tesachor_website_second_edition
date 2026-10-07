<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Models\Province;

class GuestProvinceController extends Controller
{

    public function index()
    {
        //* List all province inside this page
        $provinces = Province::orderBy('name', 'asc')->paginate(12);
        return view('guests.provinces.index', compact('provinces'));
    }
    //
    public function show(string $id)
    {
        //* Added Show method to list destinations in this province
        //* Click on each province and it will navigate you to the specific province with destination inside that province
        $province = Province::with('destinations')
            ->where('id', $id)       //* Check if it matches an ID
            ->orWhere('slug', $id)   //* OR check if it matches a Slug
            ->firstOrFail();         //* If neither works, THEN show 404
        //* Guests directory
        return view('guests.provinces.show', compact('province'));
    }
}
