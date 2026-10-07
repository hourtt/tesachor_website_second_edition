<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Destination;

class UserDestinationController extends Controller
{
    public function show(string $id)
    {
        // Find by ID or Slug to prevent 404 errors if using slug in URL
        $destination = Destination::with('province')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return view('auth.users.destinations.show', compact('destination'));
    }
}
