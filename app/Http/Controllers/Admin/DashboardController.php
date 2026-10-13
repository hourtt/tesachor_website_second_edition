<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Province;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'provinces'    => Province::count(),
            'destinations' => Destination::count(),
            'users'        => User::count(),
            'recommended'  => Destination::where('is_recommended', true)->count(),
        ];

        $recentProvinces    = Province::latest()->take(5)->get();
        $recentDestinations = Destination::with('province')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProvinces', 'recentDestinations'));
    }
}
