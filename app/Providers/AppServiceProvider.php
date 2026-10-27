<?php

namespace App\Providers;

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

        ResetPassword::createUrlUsing(fn(object $notifiable, string $token) => route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]));
    }
}
