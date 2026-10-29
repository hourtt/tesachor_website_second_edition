<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\DestinationService;
use App\Services\ProvinceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function __construct(
        protected ProvinceService $provinceService,
        protected DestinationService $destinationService
    ) {
    }

    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admins.dashboard');
        }

        $filters = $request->only(['search', 'category', 'category_id']);
        $provinces = $this->provinceService->getProvincesPaginated(8);
        $destinations = $this->destinationService->getFilteredDestinations($filters, 12);
        $cards = config('landing.cards');
        $discover_cambodia_cards = config('landing.discover_cambodia_cards');
        $features = config('landing.features');
        $tips = config('landing.tips');

        $activeCategoryName = null;
        if (!empty($filters['category'])) {
            $catParam = $filters['category'];
            $cat = is_numeric($catParam)
                ? Category::find((int) $catParam)
                : Category::where('slug', $catParam)->orWhere('name', $catParam)->first();
            if ($cat) {
                $activeCategoryName = \Illuminate\Support\Facades\Lang::has('landing/landing.' . $cat->slug)
                    ? __('landing/landing.' . $cat->slug)
                    : $cat->name;
            } else {
                $activeCategoryName = ucfirst((string) $catParam);
            }
        }

        return view('public.landing', compact(
            'provinces',
            'destinations',
            'cards',
            'discover_cambodia_cards',
            'features',
            'tips',
            'activeCategoryName'
        ));
    }
}
