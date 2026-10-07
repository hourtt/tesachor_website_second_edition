<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDestinationController extends Controller
{
    // public function index()
    // {
    //     $destinations = Destination::with('province')->latest()->paginate(10);
    //     return view('auth.admins.destination.index', compact('destinations'));
    // }

    public function create(Request $request)
    {
        $provinces = Province::all();

        // * Define the targetProvince Variable (purpose: to check which place that came from earlier. Ex: if you came from siem reap then when you go back, you will navigate back to siem reap)
        $targetProvince = null;
        if ($request->has('province_id')) {
            // * Find the province based on the provided province_id in the request
            $targetProvince = Province::find($request->province_id, ['*']);
        }

        return view('auth.admins.destinations.create', compact('provinces', 'targetProvince'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'place_name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_recommended' => 'boolean',
            'place_description' => 'required|string',
            'place_history' => 'nullable|string',
            'place_information' => 'nullable|string',
            'highlights' => 'nullable|array',
            'highlights.*' => 'string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('destination_thumbnails', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $file) {
                $galleryPaths[] = $file->store('destination_gallery', 'public');
            }
            $data['gallery_images'] = $galleryPaths;
        }

        if (! empty($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn($value) => ! is_null($value) && $value !== ''));
        }

        $data['slug'] = Str::slug($request->place_name);
        $data['is_recommended'] = $request->has('is_recommended');

        Destination::create($data);

        $province = Province::findOrFail($data['province_id']);

        return redirect()->route('admins.provinces.show', $province->slug)
            ->with('success', 'Destination created successfully!');
    }

    public function show(string $id)
    {
        // Find by ID or Slug to prevent 404 errors if using slug in URL
        $destination = Destination::with('province')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return view('auth.admins.destinations.show', compact('destination'));
    }

    public function edit(string $id)
    {
        $destination = Destination::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $provinces = Province::all();

        return view('auth.admins.destinations.edit', compact('destination', 'provinces'));
    }

    public function update(Request $request, string $id)
    {
        $destination = Destination::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $data = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'place_name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_recommended' => 'boolean',
            'place_description' => 'required|string',
            'place_history' => 'nullable|string',
            'place_information' => 'nullable|string',
            'highlights' => 'nullable|array',
            'highlights.*' => 'string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($destination->thumbnail) {
                Storage::disk('public')->delete($destination->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('destination_thumbnails', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            if ($destination->gallery_images) {
                foreach ($destination->gallery_images as $oldImg) {
                    if (Storage::disk('public')->exists($oldImg)) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $file) {
                $galleryPaths[] = $file->store('destination_gallery', 'public');
            }
            $data['gallery_images'] = $galleryPaths;
        }

        if (isset($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn($value) => ! is_null($value) && $value !== ''));
        }

        if ($destination->place_name !== $request->place_name) {
            $data['slug'] = Str::slug($request->place_name);
        }

        $data['is_recommended'] = $request->has('is_recommended');

        $destination->update($data);

        return redirect()->route('admins.destinations.index')
            ->with('success', 'Destination updated successfully!');
    }

    public function destroy(string $id)
    {
        $destination = Destination::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

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
        // * Delete the destination record from the database based on the provided ID
        $destination->delete($id);

        return redirect()->back()->with('success', 'Destination deleted successfully!');
    }
}
