<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;
use App\Services\DestinationService;
use Illuminate\Support\Facades\Auth;

class GuestDashboardController extends Controller
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
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admins.dashboard');
        }

        $provinces = $this->provinceService->getProvincesPaginated(8);
        $destinations = $this->destinationService->getRecommendedDestinations(6);

        return view('guests.dashboard', compact('provinces', 'destinations'));
    }
}
