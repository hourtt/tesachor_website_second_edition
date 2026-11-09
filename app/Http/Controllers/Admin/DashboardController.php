<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Province;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $stats = [
                'provinces' => Province::count(),
                'destinations' => Destination::count(),
                'recommended' => Destination::where('is_recommended', true)->count(),
            ];

            $recentProvinces = Province::withCount('destinations')->latest()->take(5)->get();
            $recentDestinations = Destination::with('province')->latest()->take(5)->get();
        } catch (\Throwable $e) {
            report($e);

            $stats = [
                'provinces' => 0,
                'destinations' => 0,
                'recommended' => 0,
            ];
            $recentProvinces = collect();
            $recentDestinations = collect();

            session()->flash('error', 'Could not retrieve latest database metrics. Please refresh or try again.');
        }

        return view('admin.dashboard', compact('stats', 'recentProvinces', 'recentDestinations'));
    }
}
