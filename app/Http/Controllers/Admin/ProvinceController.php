<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProvinceRequest;
use App\Http\Requests\Admin\UpdateProvinceRequest;
use App\Services\ProvinceService;

// TODO(SUPABASE): inject SupabaseService here for image upload/delete
// use App\Services\SupabaseService;

class ProvinceController extends Controller
{
    public function __construct(
        protected ProvinceService $provinceService,
    ) {}

    public function index()
    {
        $provinces = $this->provinceService->getProvincesPaginated(12);

        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('admin.provinces.create');
    }

    public function store(StoreProvinceRequest $request)
    {
        // TODO(SUPABASE): replace $request->file('thumbnail') with
        //   $this->supabase->uploadImage($request->file('thumbnail'), 'provinces')
        //   and store the returned URL instead of a local path.
        $this->provinceService->createProvince(
            $request->validated(),
            $request->file('thumbnail')
        );

        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province created successfully!');
    }

    public function show(string $slug)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($slug);

        return view('admin.provinces.show', compact('province'));
    }

    public function edit(string $slug)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($slug);

        return view('admin.provinces.edit', compact('province'));
    }

    public function update(UpdateProvinceRequest $request, string $slug)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($slug);

        // TODO(SUPABASE): replace $request->file('thumbnail') with
        //   $this->supabase->uploadImage($request->file('thumbnail'), 'provinces')
        //   and delete the old image: $this->supabase->deleteImage($province->thumbnail)
        $this->provinceService->updateProvince(
            $province,
            $request->validated(),
            $request->file('thumbnail')
        );

        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province updated successfully!');
    }

    public function destroy(string $slug)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($slug);

        // TODO(SUPABASE): also call $this->supabase->deleteImage($province->thumbnail)
        if (! $this->provinceService->deleteProvince($province)) {
            return redirect()->back()
                ->with('error', 'Cannot delete province because it has destinations.');
        }

        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province deleted successfully!');
    }
}
