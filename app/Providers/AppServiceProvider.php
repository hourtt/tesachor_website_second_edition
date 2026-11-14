<?php

namespace App\Providers;

use App\View\Composers\NavigationComposer;
use DateTimeInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\PostgresConnection;
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
        // Fix for PostgreSQL with emulated prepared statements (Supabase Transaction Pooler port 6543):
        // Laravel's default Connection::prepareBindings casts booleans to integer (1 / 0),
        // which causes PostgreSQL to fail with "operator does not exist: boolean = integer".
        // Binding booleans as 'true' / 'false' string literals resolves this cleanly without database alterations.
        Connection::resolverFor('pgsql', function ($connection, $database, $prefix, $config) {
            return new class ($connection, $database, $prefix, $config) extends PostgresConnection {
                public function prepareBindings(array $bindings)
                {
                    $grammar = $this->getQueryGrammar();

                    foreach ($bindings as $key => $value) {
                        if ($value instanceof DateTimeInterface) {
                            $bindings[$key] = $value->format($grammar->getDateFormat());
                        } elseif (\is_bool($value)) {
                            $bindings[$key] = $value ? 'true' : 'false';
                        }
                    }

                    return $bindings;
                }
            };
        });
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
        RateLimiter::for('uploads', fn(Request $request) => Limit::perMinute('10')->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('public', fn(Request $request) => Limit::perMinute('120')->by($request->ip()));
    }
}
