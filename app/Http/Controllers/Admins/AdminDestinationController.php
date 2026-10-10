<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Services\DestinationService;
use Illuminate\Http\Request;

class AdminDestinationController extends Controller
{
    protected $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    public function create(Request $request)
    {
        $provinces = Province::all();
        $targetProvince = null;
        if ($request->has('province_id')) {
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

        $data['is_recommended'] = $request->has('is_recommended');

        $this->destinationService->createDestination(
            $data, 
            $request->file('thumbnail'), 
            $request->file('gallery_images')
        );

        $province = Province::findOrFail($data['province_id']);

        return redirect()->route('admins.provinces.show', $province->slug)
            ->with('success', 'Destination created successfully!');
    }

    public function show(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        return view('auth.admins.destinations.show', compact('destination'));
    }

    public function edit(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        $provinces = Province::all();
        return view('auth.admins.destinations.edit', compact('destination', 'provinces'));
    }

    public function update(Request $request, string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);

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

        $data['is_recommended'] = $request->has('is_recommended');

        $this->destinationService->updateDestination(
            $destination, 
            $data, 
            $request->file('thumbnail'), 
            $request->file('gallery_images')
        );

        return redirect()->route('admins.destinations.index')
            ->with('success', 'Destination updated successfully!');
    }

    public function destroy(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        $this->destinationService->deleteDestination($destination);

        return redirect()->back()->with('success', 'Destination deleted successfully!');
    }
}
