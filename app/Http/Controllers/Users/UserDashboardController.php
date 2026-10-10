<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;
use App\Services\DestinationService;

class UserDashboardController extends Controller
{
    protected $provinceService;
    protected $destinationService;

    public function __construct(ProvinceService $provinceService, DestinationService $destinationService)
    {
        $this->provinceService = $provinceService;
        $this->destinationService = $destinationService;
    }

    public function index()
    {
        $provinces = $this->provinceService->getProvincesPaginated(8);
        $destinations = $this->destinationService->getRecommendedDestinations(6);

        return view('auth.users.dashboard', compact('provinces', 'destinations'));
    }
}
