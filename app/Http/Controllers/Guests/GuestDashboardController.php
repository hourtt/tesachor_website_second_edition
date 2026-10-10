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
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admins.dashboard');
            }
            return redirect()->route('users.dashboard');
        }

        $provinces = $this->provinceService->getProvincesPaginated(4);
        $destinations = $this->destinationService->getRecommendedDestinations(6);

        return view('guests.dashboard', compact('provinces', 'destinations'));
    }
}
