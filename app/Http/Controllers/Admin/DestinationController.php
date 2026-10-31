<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Province;
use App\Services\DestinationService;
use Illuminate\Http\Request;

// TODO(SUPABASE): inject SupabaseService for image upload/delete/gallery
// use App\Services\SupabaseService;

class DestinationController extends Controller
{
    public function __construct(
        protected DestinationService $destinationService,
        // TODO(SUPABASE): protected SupabaseService $supabase,
    ) {}

    public function index(Request $request)
    {
        $query = Destination::with(['province', 'category']);

        // Text search across place_name and short_description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('place_name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Category filter (supports category_id or slug)
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        } elseif ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Province filter (combines with category and text search)
        if ($provinceId = $request->input('province_id')) {
            $query->where('province_id', $provinceId);
        }

        $destinations = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();

        return view('admin.destinations.index', compact('destinations', 'categories', 'provinces'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $targetProvince = request()->has('province_id')
            ? Province::find(request()->province_id)
            : null;

        return view('admin.destinations.create', compact('provinces', 'categories', 'targetProvince'));
    }

    public function store(StoreDestinationRequest $request)
    {
        try {
            $destination = $this->destinationService->createDestination(
                $request->validated(),
                $request->file('thumbnail'),
                $request->file('gallery_images')
            );

            $province = Province::find($destination->province_id);

            return redirect()->route('admins.provinces.show', $province ? $province->slug : $destination->province_id)
                ->with('success', 'Destination created successfully!');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not save the destination, please try again.');
        }
    }

    public function show(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);

        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        $provinces = Province::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.destinations.edit', compact('destination', 'provinces', 'categories'));
    }

    public function update(UpdateDestinationRequest $request, string $id)
    {
        try {
            $destination = $this->destinationService->getDestinationByIdOrSlug($id);

            $this->destinationService->updateDestination(
                $destination,
                $request->validated(),
                $request->file('thumbnail'),
                $request->file('gallery_images')
            );

            return redirect()->route('admins.destinations.index')
                ->with('success', 'Destination updated successfully!');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not save the destination, please try again.');
        }
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
