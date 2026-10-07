<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Destination;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Fetch Provinces here too!
        $provinces =  Province::orderBy('name', 'asc')->paginate(8);

        $destination = Destination::where('is_recommended', true)
            ->with('province')
            ->latest()
            ->take(6)
            ->get();

        return view('auth.users.dashboard', compact('provinces', 'destination'));
    }
}
