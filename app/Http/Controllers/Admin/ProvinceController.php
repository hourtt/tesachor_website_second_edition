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
        $provinces = $this->provinceService->getProvincesPaginated(8);

        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('admin.provinces.create');
    }

    public function store(StoreProvinceRequest $request)
    {
        try {
            $this->provinceService->createProvince(
                $request->validated(),
                $request->file('thumbnail')
            );

            return redirect()->route('admins.provinces.index')
                ->with('success', 'Province created successfully!');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not save the province, please try again.');
        }
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
        try {
            $province = $this->provinceService->getProvinceByIdOrSlug($slug);

            $this->provinceService->updateProvince(
                $province,
                $request->validated(),
                $request->file('thumbnail')
            );

            return redirect()->route('admins.provinces.index')
                ->with('success', 'Province updated successfully!');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not save the province, please try again.');
        }
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
