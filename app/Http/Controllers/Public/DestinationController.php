<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\DestinationService;

class DestinationController extends Controller
{
    public function __construct(protected DestinationService $destinationService) {}

    public function show(string $id)
    {
        $destination = $this->destinationService->getDestinationByIdOrSlug($id);

        return view('public.destinations.show', compact('destination'));
    }
}
