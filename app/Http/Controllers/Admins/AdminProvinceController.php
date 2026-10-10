<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;
use Illuminate\Http\Request;

class AdminProvinceController extends Controller
{
    protected $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function index()
    {
        $provinces = $this->provinceService->getProvincesPaginated(12);
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

        $this->provinceService->createProvince($data, $request->file('thumbnail'));

        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province created successfully!');
    }

    public function show(string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);
        return view('auth.admins.provinces.show', compact('province'));
    }

    public function edit(string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);
        return view('auth.admins.provinces.edit', compact('province'));
    }

    public function update(Request $request, string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);
        
        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:provinces,name,' . $province->id,
            'name_in_khmer' => 'required|string|max:255',
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->provinceService->updateProvince($province, $data, $request->file('thumbnail'));
        
        return redirect()->route('admins.provinces.index')
            ->with('success', 'Province updated successfully!');
    }

    public function destroy(string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);
        
        if (!$this->provinceService->deleteProvince($province)) {
            return redirect()->back()->with('error', 'Cannot delete province because it contains destinations.');
        }

        return redirect()->back()->with('success', 'Province deleted successfully!');
    }
}
