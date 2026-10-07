<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Province;

class UserProvinceController extends Controller
{

    public function index()
    {
        $provinces = Province::orderBy('name', 'asc')->paginate(12);
        return view('auth.users.provinces.index', compact('provinces'));
    }
    public function show(string $id)
    {

        // Added Show method to list destinations in this province
        $province = Province::with('destinations')
            ->where('id', $id)       // Check if it matches an ID
            ->orWhere('slug', $id)   // OR check if it matches a Slug
            ->firstOrFail();         // If neither works, THEN show 404
        return view('auth.users.provinces.show', compact('province'));
    }
}
