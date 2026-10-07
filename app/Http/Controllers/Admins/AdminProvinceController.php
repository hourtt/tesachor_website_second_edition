<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Province;

class AdminProvinceController extends Controller
{
    public function index()
    {
        // 'asc' means Ascending (A-Z)
        $provinces = Province::orderBy('name', 'asc')->paginate(12);
        return view('auth.admins.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('auth.admins.provinces.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:provinces,name',
            'name_in_khmer' => 'required|string|max:255',
            'thumbnail'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('province_thumbnails', 'public');
        }

        $data['slug'] = Str::slug($request->name);

        Province::create($data);

        // Fixed Redirect: Points to the Index list, not Dashboard
        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province created successfully!');
    }

    public function show(string $id)
    {
        // Added Show method to list destinations in this province
        $province = Province::with('destinations')
            ->where('id', $id)       // Check if it matches an ID
            ->orWhere('slug', $id)   // OR check if it matches a Slug
            ->firstOrFail();         // If neither works, THEN show 404
        return view('auth.admins.provinces.show', compact('province'));
    }

    public function edit(string $id)
    {
        $province = Province::with('destinations')
            ->where('id', $id)       // Check if it matches an ID
            ->orWhere('slug', $id)   // OR check if it matches a Slug
            ->firstOrFail();         // If neither works, THEN show 404
        return view('auth.admins.provinces.edit', compact('province'));
    }

    public function update(Request $request, string $id)
    {
        $province = Province::with('destinations')
            ->where('id', $id)       // Check if it matches an ID
            ->orWhere('slug', $id)   // OR check if it matches a Slug
            ->firstOrFail();         // If neither works, THEN show 404
        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:provinces,name,' . $id,
            'name_in_khmer' => 'required|string|max:255',
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($province->thumbnail) {
                Storage::disk('public')->delete($province->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('province_thumbnails', 'public');
        }

        if ($province->name !== $request->name) {
            $data['slug'] = Str::slug($request->name);
        }

        $province->update($data);
        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province updated successfully!');
    }

    public function destroy(string $id)
    {
        $province = Province::with('destinations')
            ->where('id', $id)       // Check if it matches an ID
            ->orWhere('slug', $id)   // OR check if it matches a Slug
            ->firstOrFail();         // If neither works, THEN show 404
        if ($province->destinations()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete province because it contains destinations.');
        }

        if ($province->thumbnail) {
            Storage::disk('public')->delete($province->thumbnail);
        }

        //* Deleting the province record base on the given id
        $province->delete($id);

        return redirect()->back()->with('success', 'Province deleted successfully!');
    }
}
