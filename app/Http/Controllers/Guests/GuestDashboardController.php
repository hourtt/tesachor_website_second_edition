<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Destination;
use Illuminate\Support\Facades\Auth;

class GuestDashboardController extends Controller
{
    //
    public function index()
    {
        // Redirect to their specific dashboard if Authenticated (Prevent routing leaked)
        if (Auth::guard('admins')->check()) {
            return redirect()->route('admins.dashboard');
        }
        if (Auth::guard('web')->check()) {
            return redirect()->route('users.dashboard');
        }

        // Fetch Provinces for the dashboard slider
        $provinces = Province::orderBy('name', 'asc')->paginate(4);

        //* Rename from $item -> $destinations
        // We use the Destination model here to find items where is_recommended = true
        $destinations = Destination::where('is_recommended', true)
            ->with('province')
            ->latest()
            ->take(6)
            ->get();

        // Return the Dashboard View
        return view('guests.dashboard', compact('provinces', 'destinations'));
    }
}
