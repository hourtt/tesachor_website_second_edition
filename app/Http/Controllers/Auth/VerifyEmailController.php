<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $guard = 'web';

        if ($request->user($guard)->hasVerifiedEmail()) {
            return redirect()->intended(route($guard === 'admins' ? 'admins.dashboard' : 'public.landing', absolute: false).'?verified=1');
        }

        if ($request->user($guard)->markEmailAsVerified()) {
            event(new Verified($request->user($guard)));
        }

        return redirect()->intended(route($guard === 'admins' ? 'admins.dashboard' : 'public.landing', absolute: false).'?verified=1');
    }
}
