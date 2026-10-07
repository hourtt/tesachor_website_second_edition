<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $guard = Auth::guard('admins')->check() ? 'admins' : 'web';
        $user = $request->user($guard);

        return $user->hasVerifiedEmail()
                    ? redirect()->intended(route($guard === 'admins' ? 'admins.dashboard' : 'users.dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
