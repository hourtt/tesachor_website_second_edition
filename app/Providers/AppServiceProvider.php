<?php

namespace App\Providers;

use App\Database\PostgresConnection as AppPostgresConnection;
use App\View\Composers\NavigationComposer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Connection::resolverFor('pgsql', fn($connection, $database, $prefix, $config) => new AppPostgresConnection($connection, $database, $prefix, $config));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Class in a Service Provider after create a navigation composer class
        View::composer('layouts.navigation', NavigationComposer::class);
        Model::preventLazyLoading(!app()->isProduction());
        ResetPassword::createUrlUsing(fn(object $notifiable, string $token) => route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]));

        //* Limit the image upload to prevent spamming
        RateLimiter::for('uploads', fn(Request $request) => Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('public', fn(Request $request) => Limit::perMinute(120)->by($request->ip()));
    }
}
