<?php

namespace App\View\Composers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use function is_array;

class NavigationComposer
{
    /**
     * Bind navigation data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();
        $auth = Auth::check();

        // 1. Initialize route variables
        $dashboard = 'users.login';
        $profile = 'profile.edit';
        $logout = 'logout';
        $logoRoute = 'public.landing';

        $isAdmin = (bool) ($auth && $user instanceof User && $user->hasRole('admin'));

        // 2. Check for Admin
        if ($isAdmin) {
            $dashboard = 'admins.dashboard';
            $profile = 'admins.profile.edit';
            $logout = 'logout';
            $logoRoute = 'admins.dashboard';
        }

        // 3. Check for Regular User
        elseif ($auth && $user instanceof User) {
            $dashboard = 'public.landing';
            $profile = 'profile.edit';
            $logout = 'logout';
            $logoRoute = 'public.landing';
        }

        $isProfilePage = request()->routeIs('*.profile.*', 'profile.*');

        // 4. Retrieve nav items from config and format labels
        $rawItems = config('navigation.items', []);
        $navItems = array_map(function ($item) {
            $labelKey = $item['label_key'] ?? ('navbar.'.$item['id']);

            return [
                'id' => $item['id'],
                'label' => __($labelKey),
            ];
        }, $rawItems);

        // 5. Build reactive translation dictionary for Alpine i18n
        $navTranslations = [
            'en' => [
                'navbar' => trans('navbar', [], 'en'),
                'landing' => trans('landing/landing', [], 'en'),
                'landing/landing' => trans('landing/landing', [], 'en'),
            ],
            'km' => [
                'navbar' => trans('navbar', [], 'km'),
                'landing' => trans('landing/landing', [], 'km'),
                'landing/landing' => trans('landing/landing', [], 'km'),
            ],
        ];

        foreach (['en', 'km'] as $loc) {
            $landingArr = trans('landing/landing', [], $loc);
            if (is_array($landingArr)) {
                $navTranslations[$loc] += $landingArr;
            }
        }

        $view->with([
            'user' => $user,
            'auth' => $auth,
            'isAdmin' => $isAdmin,
            'dashboard' => $dashboard,
            'profile' => $profile,
            'logout' => $logout,
            'logoRoute' => $logoRoute,
            'isProfilePage' => $isProfilePage,
            'navItems' => $navItems,
            'navTranslations' => $navTranslations,
        ]);
    }
}
