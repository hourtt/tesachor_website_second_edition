<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Models\Province;
use App\Services\DestinationService;
// TODO(SUPABASE): inject SupabaseService for image upload/delete/gallery
// use App\Services\SupabaseService;

class DestinationController extends Controller
{
    public function __construct(
        protected DestinationService $destinationService,
        // TODO(SUPABASE): protected SupabaseService $supabase,
    ) {}

    public function index()
    {
        $destinations = \App\Models\Destination::with('province')
            ->latest()
            ->paginate(15);

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $provinces     = Province::orderBy('name')->get();
        $targetProvince = request()->has('province_id')
            ? Province::find(request()->province_id)
            : null;

        return view('admin.destinations.create', compact('provinces', 'targetProvince'));
    }

    public function store(StoreDestinationRequest $request)
    {
        // TODO(SUPABASE): replace $request->file('thumbnail') with
        //   $this->supabase->uploadImage($request->file('thumbnail'), 'destinations')
        //   For gallery: loop and call $this->supabase->uploadImage($file, 'destination_gallery')
        $this->destinationService->createDestination(
            $request->validated(),
            $request->file('thumbnail'),
            $request->file('gallery_images')
        );

        $province = Province::findOrFail($request->validated()['province_id']);

        return redirect()->route('admins.provinces.show', $province->slug)
            ->with('success', 'Destination created successfully!');
    }

    public function show(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        $provinces   = Province::orderBy('name')->get();
        return view('admin.destinations.edit', compact('destination', 'provinces'));
    }

    public function update(UpdateDestinationRequest $request, string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);

        // TODO(SUPABASE): replace local file handling with:
        //   - thumbnail: $this->supabase->uploadImage($request->file('thumbnail'), 'destinations')
        //     and $this->supabase->deleteImage($destination->thumbnail)
        //   - gallery: same pattern per file
        $this->destinationService->updateDestination(
            $destination,
            $request->validated(),
            $request->file('thumbnail'),
            $request->file('gallery_images')
        );

        return redirect()->route('admins.destinations.index')
            ->with('success', 'Destination updated successfully!');
    }

    public function destroy(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);

        // TODO(SUPABASE): also call:
        //   $this->supabase->deleteImage($destination->thumbnail)
        //   foreach ($destination->gallery_images as $img) { $this->supabase->deleteImage($img); }
        $this->destinationService->deleteDestination($destination);

        return redirect()->back()->with('success', 'Destination deleted successfully!');
    }
}
