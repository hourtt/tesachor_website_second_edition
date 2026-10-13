<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileAvatarController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user = $request->user();
        $oldPath = $user->avatar_path;

        $path = Storage::disk('supabase')->putFile(
            "avatars/{$user->id}",
            $request->file('avatar')
        );

        $user->avatar_path = $path;
        $user->save();

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('supabase')->delete($oldPath);
        }

        return back()->with('status', 'avatar-updated');
    }

    /**
     * Remove the user's avatar.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('supabase')->delete($user->avatar_path);
            $user->avatar_path = null;
            $user->save();
        }

        return back()->with('status', 'avatar-deleted');
    }
}