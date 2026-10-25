<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $guard = 'web';
        $user = $request->user($guard);

        if ($request->routeIs('admins.*')) {
            return view('admin.profile.edit', [
                'user' => $user,
            ]);
        }

        if ($user?->hasRole('admin')) {
            return redirect()->route('admins.profile.edit');
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ProfileService $profileService): RedirectResponse
    {
        $guard = 'web';
        $profileService->update($request->user($guard), $request->validated());

        if ($request->routeIs('admins.*')) {
            $redirect = Redirect::route('admins.profile.edit')->with('status', 'profile-updated');
            if ($request->filled('email') && ! $request->filled('name')) {
                $redirect->with('email_updated', true);
            }

            return $redirect;
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request, ProfileService $profileService): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $guard = 'web';
        $user = $request->user($guard);

        Auth::guard($guard)->logout();
        $profileService->delete($user);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
