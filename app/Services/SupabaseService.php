<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * SupabaseService — placeholder for Supabase integration.
 *
 * All methods currently delegate to the local `public` disk so the app
 * works without Supabase. Once you are ready to connect Supabase:
 *
 *   1. Fill in SUPABASE_URL, SUPABASE_KEY, SUPABASE_SERVICE_ROLE_KEY,
 *      SUPABASE_BUCKET in your .env (see .env.example).
 *   2. Replace the bodies of each method below with the real Supabase
 *      Storage SDK / REST API calls.
 *   3. Search for // TODO(SUPABASE): across the codebase to find every
 *      integration point that calls this service.
 */
class SupabaseService
{
    // TODO(SUPABASE): inject the Supabase PHP SDK client here when available.
    // private $client;

    /**
     * Upload a single image to Supabase Storage and return its public URL.
     *
     * @param  UploadedFile  $file  The uploaded file.
     * @param  string  $folder  Storage folder/path prefix (e.g. 'provinces').
     * @return string The stored path (local) or public URL (Supabase).
     *
     * TODO(SUPABASE): Replace body with:
     *   $path = $folder . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
     *   $this->client->storage->from(config('supabase.bucket'))->upload($path, fopen($file->getRealPath(), 'r'));
     *   return $this->getImageUrl($path);
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }

    /**
     * Delete an image from Supabase Storage by its path.
     *
     * @param  string|null  $path  The stored path or Supabase object key.
     *
     * TODO(SUPABASE): Replace body with:
     *   $this->client->storage->from(config('supabase.bucket'))->remove([$path]);
     */
    public function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get the public URL for a stored image.
     *
     * @param  string  $path  The stored path.
     * @return string The public URL.
     *
     * TODO(SUPABASE): Replace body with:
     *   return config('supabase.storage_public_url') . '/object/public/' . config('supabase.bucket') . '/' . $path;
     */
    public function getImageUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Synchronize a user's role and active status to Supabase Auth.
     *
     * @param  int|string  $userId  The local user ID.
     * @param  bool  $isActive  Whether the user is active.
     * @param  string  $role  The user's role name.
     *
     * TODO(SUPABASE): Replace body with a call to Supabase Admin API:
     *   POST /auth/v1/admin/users/{userId}
     *   Body: { "ban_duration": $isActive ? "none" : "876600h", "user_metadata": { "role": $role } }
     *   Using SUPABASE_SERVICE_ROLE_KEY in the Authorization header.
     */
    public function syncUserStatus(int $userId, bool $isActive, string $role): void
    {
        // TODO(SUPABASE): implement Supabase Auth user sync.
    }

    /**
     * Create a new Supabase Auth user (called on registration).
     *
     * @return array|null The Supabase user object or null on failure.
     *
     * TODO(SUPABASE): Replace with:
     *   POST /auth/v1/admin/users  { email, password, email_confirm: true }
     */
    public function createAuthUser(string $email, string $password): ?array
    {
        // TODO(SUPABASE): implement Supabase Auth user creation.
        return null;
    }

    /**
     * Retrieve destination data from Supabase (if using Supabase as primary DB).
     *
     * TODO(SUPABASE): Replace with:
     *   GET /rest/v1/destinations?select=*  (using SUPABASE_KEY)
     */
    public function getDestinations(): array
    {
        // TODO(SUPABASE): implement Supabase data fetch for destinations.
        return [];
    }

    /**
     * Retrieve province data from Supabase (if using Supabase as primary DB).
     *
     * TODO(SUPABASE): Replace with:
     *   GET /rest/v1/provinces?select=*  (using SUPABASE_KEY)
     */
    public function getProvinces(): array
    {
        // TODO(SUPABASE): implement Supabase data fetch for provinces.
        return [];
    }
}
