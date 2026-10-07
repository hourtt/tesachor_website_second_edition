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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated as 'web' (User)
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        // 2. Check if user is authenticated as 'admins' (Admin)
        if (Auth::guard('admins')->check()) {
            // User is an Admin trying to access User route -> Redirect to Admin Dashboard
            return redirect()->route('admins.dashboard');
        }

        // 3. Not authenticated at all -> Redirect to Login
        return redirect()->route('login');
    }
}
