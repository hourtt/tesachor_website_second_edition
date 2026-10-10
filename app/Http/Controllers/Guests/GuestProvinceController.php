<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;

class GuestProvinceController extends Controller
{
    protected $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function index()
    {
        $provinces = $this->provinceService->getProvincesPaginated(12);
        return view('guests.provinces.index', compact('provinces'));
    }

    public function show(string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);
        return view('guests.provinces.show', compact('province'));
    }
}
