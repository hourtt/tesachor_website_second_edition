<?php

namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use App\Services\DestinationService;

class GuestDestinationController extends Controller
{
    protected $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    public function show(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);
        return view('guests.destinations.show', compact('destination'));
    }
}
