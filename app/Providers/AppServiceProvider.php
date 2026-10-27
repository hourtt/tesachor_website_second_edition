<?php

namespace App\Providers;

use App\Models\Admin;
use App\View\Composers\NavigationComposer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Class in a Service Provider after create a navigation composer class 
        View::composer('layouts.navigation', NavigationComposer::class);

        ResetPassword::createUrlUsing(function (object $notifable, string $token) {
            if ($notifable instanceof Admin) {
                return route('admins.password.reset', ['token' => $token, 'email' => $notifable->getEmailForPasswordReset()]);
            } else {
                return route('users.password.reset', ['token' => $token, 'email' => $notifable->getEmailForPasswordReset()]);
            }
        });
    }
}
