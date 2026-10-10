<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\DestinationService;

class UserDestinationController extends Controller
{
    protected $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    public function show(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        return view('auth.users.destinations.show', compact('destination'));
    }
}
