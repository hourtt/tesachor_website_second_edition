<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We check if the email belongs to an admin or user to determine the broker
        // This is a simple heuristic. If you want strict separation, you would need separate forms or inputs.
        // Assuming 'admins' broker is configured in auth.php
        
        $status = null;
        
        // Try to send to Admin first if email exists in admins table? 
        // Or check existence. 
        // For simplicity with "Unified" flow, we might need to guess.
        // Let's try sending to 'users' defaulting. If strictly 'role based from DB', we might need to check DB.
        
        $broker = 'users';
        if (\App\Models\Admin::where('email', $request->email)->exists()) {
            $broker = 'admins';
        }

        $status = Password::broker($broker)->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
