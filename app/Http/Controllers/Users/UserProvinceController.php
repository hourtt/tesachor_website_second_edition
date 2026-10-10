<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;

class UserProvinceController extends Controller
{
    protected $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function index()
    {
        $provinces = $this->provinceService->getProvincesPaginated(12);
        return view('auth.users.provinces.index', compact('provinces'));
    }

    public function show(string $id)
    {
        $province = $this->provinceService->getProvinceByIdOrSlug($id);
        return view('auth.users.provinces.show', compact('province'));
    }
}
