<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // We need to modify the LoginRequest or handle manual auth here
        $request->session()->regenerate();

        //* Admin dashboard
        if (Auth::guard('admins')->check()) {
            return redirect()->intended(route('admins.dashboard'));
        }

        //* User dashboard
        if (Auth::guard('web')->check()) {
             return redirect()->intended(route('users.dashboard', absolute: false));
        }
        //* Guest dashboard
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout both guards to be safe
        Auth::guard('admins')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('guests.dashboard');
    }
}
