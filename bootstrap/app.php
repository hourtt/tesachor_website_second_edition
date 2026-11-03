<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware
        $middleware->alias([
            'prevent-back-history' => PreventBackHistory::class,
            'admin' => AdminMiddleware::class,
            'user' => UserMiddleware::class,
            'active' => EnsureUserIsActive::class,
        ]);

        // Stealth 404: Never redirect unauthorized guests to secret admin login URL
        $middleware->redirectGuestsTo(fn (Request $request) => abort(404));

        // Append to the web group so every request checks user status and applies session locale
        $middleware->web(append: [
            EnsureUserIsActive::class,
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
