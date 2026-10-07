<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated as 'admins' (Admin)
        if (Auth::guard('admins')->check()) {
            return $next($request);
        }

        // 2. Check if user is authenticated as 'web' (User)
        if (Auth::guard('web')->check()) {
            // User is a regular User trying to access Admin route -> Redirect to User Dashboard
            return redirect()->route('users.dashboard');
        }

        // 3. Not authenticated at all -> Redirect to Login
        // Note: You might want to redirect to admin login if you have a separate one, 
        // otherwise standard login is fine.
        return redirect()->route('login');
    }
}
