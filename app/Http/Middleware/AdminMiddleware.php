<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Stealth 404: If unauthenticated guest or non-admin user requests /admin/*,
        // immediately return 404 Not Found to prevent leaking the secret admin login path.
        if (! Auth::check() || ! Auth::user()->hasRole('admin')) {
            abort(404);
        }

        return $next($request);
    }
}
