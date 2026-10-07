<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $guard = Auth::guard('admins')->check() ? 'admins' : 'web';
        $user = $request->user($guard);

        if ($user->hasVerifiedEmail()) {
             return redirect()->intended(route($guard === 'admins' ? 'admins.dashboard' : 'users.dashboard', absolute: false));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
