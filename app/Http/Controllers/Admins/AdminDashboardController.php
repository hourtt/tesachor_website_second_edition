<?php


namespace App\Http\Controllers\Admins;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Destination; // <--- Import the Destination Model

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Fetch Provinces for the dashboard slider
        $provinces = Province::orderBy('name', 'asc')->paginate(8);

        //* Rename from $item -> $destinations
        // We use the Destination model here to find items where is_recommended = true
        $destinations = Destination::where('is_recommended', true)
            ->with('province')
            ->latest()
            ->take(6)
            ->get();

        // 3. Return the Dashboard View
        return view('auth.admins.dashboard', compact('provinces', 'destinations'));
    }
}
