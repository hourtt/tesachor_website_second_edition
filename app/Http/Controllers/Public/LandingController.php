<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ProvinceService;
use App\Services\DestinationService;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{

    public function __construct(
        protected ProvinceService $provinceService,
        protected DestinationService $destinationService
    ) { }

    public function index()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admins.dashboard');
        }

        $provinces = $this->provinceService->getProvincesPaginated(8);
        $destinations = $this->destinationService->getRecommendedDestinations(6);

        return view('public.landing', compact('provinces', 'destinations'));
    }
}
