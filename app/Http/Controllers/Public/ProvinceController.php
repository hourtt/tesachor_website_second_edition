<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;

class ProvinceController extends Controller
{
    public function __construct(protected ProvinceService $provinceService) {}

    public function index()
    {
        $provinces = $this->provinceService->getProvincesPaginated(8);

        return view('public.provinces.index', compact('provinces'));
    }

    public function show(string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);

        return view('public.provinces.show', compact('province'));
    }
}
