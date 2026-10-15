<?php

namespace App\Http\Controllers;

use App\Services\ProfileAvatarService;
use Illuminate\Http\Request;

class ProfileAvatarController extends Controller
{
    public function update(Request $request, ProfileAvatarService $profileAvatarService)
    {
        $user = $request->user();
        $requestFile = $request->file('avatar');

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $profileAvatarService->updateAvatar($user, $requestFile);
        return back()->with('status', 'avatar-updated');
    }

    /**
     * Remove the user's avatar.
     */
    public function destroy(Request $request, ProfileAvatarService $profileAvatarService)
    {
        $user = $request->user();
        $profileAvatarService->deleteAvatar($user);

        return back()->with('status', 'avatar-deleted');
    }
}