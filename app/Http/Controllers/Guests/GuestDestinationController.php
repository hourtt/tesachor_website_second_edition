<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Models\Destination;

class GuestDestinationController extends Controller
{
    //
    public function show(string $id)
    {
        $destination = Destination::with('province')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        // * Guests directory
        return view('guests.destinations.show', compact('destination'));
    }
}
