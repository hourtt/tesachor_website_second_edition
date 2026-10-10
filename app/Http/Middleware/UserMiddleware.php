<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For 'user' routes, we can just ensure they are logged in.
        // Or if strictly 'user' role:
        if (Auth::check()) {
            // Admins accessing user route might be okay, but if you want strict:
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admins.dashboard');
            }
            return $next($request);
        }

        return redirect()->route('login');
    }
}
