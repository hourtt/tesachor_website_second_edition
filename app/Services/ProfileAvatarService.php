<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProfileAvatarService
{
    // : User is PHP return type that allow you to specify the exact data type a function or class method must return

    // if we define : User the function must return a User object or it will throw an error

    // Request (Illuminate\Http\Request) represents the whole web request: form fields, headers, session, authenticated user, and uploaded files.

    // UploadedFile (Illuminate\Http\UploadedFile) represents one uploaded file. In your controller, $request->file('avatar') gives you that file (or null if none was uploaded).
    public function updateAvatar(User $user, UploadedFile $avatar): User
    {
        $userId = (string) $user->getKey();
        $disk = Storage::disk('supabase_avatar');
        $oldPath = $user->avatar_path;
        $newPath = $disk->putFile("avatars/{$userId}", $avatar);

        if (! $newPath) {
            throw new RuntimeException('Failed to upload the avatar');
        }

        $user->avatar_path = $newPath;
        $user->save();

        // Delete old avatar if exists
        if ($oldPath && $oldPath !== $newPath) {
            $disk->delete($oldPath);
        }

        return $user;
    }

    public function deleteAvatar(User $user): void
    {
        $oldPath = $user->avatar_path;
        $disk = Storage::disk('supabase_avatar');

        // if no path in db, nothing to delete
        if (! $oldPath) {
            return;
        }

        // Clear the database reference before deleting the remote file.
        $user->avatar_path = null;
        $user->save();

        // Delete the remote file.
        $disk->delete($oldPath);
    }
}
