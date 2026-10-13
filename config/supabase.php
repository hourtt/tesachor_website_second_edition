<?php

/**
 * Supabase configuration.
 *
 * Set the following variables in your .env file:
 *   SUPABASE_URL              — Your Supabase project URL (e.g. https://xyz.supabase.co)
 *   SUPABASE_KEY              — The anon/public key
 *   SUPABASE_SERVICE_ROLE_KEY — The service role key (server-side only, never expose to client)
 *   SUPABASE_BUCKET           — The Storage bucket name for media uploads
 *   SUPABASE_STORAGE_PUBLIC_URL — Base URL for public storage (usually {SUPABASE_URL}/storage/v1)
 *
 * Database (if switching from local MySQL to Supabase Postgres):
 *   SUPABASE_DB_HOST, SUPABASE_DB_PORT, SUPABASE_DB_DATABASE,
 *   SUPABASE_DB_USERNAME, SUPABASE_DB_PASSWORD
 *
 * TODO(SUPABASE): Once you have your project ready, fill in the .env values.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Supabase Project URL
    |--------------------------------------------------------------------------
    | Your Supabase project's base URL, e.g. https://abcdefghijklmnop.supabase.co
    */
    'url' => env('SUPABASE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase Anon Key (Public)
    |--------------------------------------------------------------------------
    | Safe to use client-side; limited by Row Level Security policies.
    */
    'key' => env('SUPABASE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase Service Role Key (Private)
    |--------------------------------------------------------------------------
    | Full admin access — NEVER expose this in front-end code or commit it.
    | Use only in server-side code (e.g. SupabaseService methods).
    */
    'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Storage Bucket
    |--------------------------------------------------------------------------
    | The name of the Supabase Storage bucket used for media uploads.
    */
    'bucket' => env('SUPABASE_BUCKET', 'media'),

    /*
    |--------------------------------------------------------------------------
    | Storage Public URL
    |--------------------------------------------------------------------------
    | Base URL for constructing public file URLs.
    | Typically: {SUPABASE_URL}/storage/v1
    */
    'storage_public_url' => env('SUPABASE_STORAGE_PUBLIC_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Database (Supabase Postgres)
    |--------------------------------------------------------------------------
    | If you connect to Supabase's managed Postgres instead of local MySQL.
    | Set these in .env and update config/database.php accordingly.
    | TODO(SUPABASE): switch DB_CONNECTION to 'pgsql' and fill these values.
    */
    'db' => [
        'host'     => env('SUPABASE_DB_HOST', '127.0.0.1'),
        'port'     => env('SUPABASE_DB_PORT', '5432'),
        'database' => env('SUPABASE_DB_DATABASE', 'postgres'),
        'username' => env('SUPABASE_DB_USERNAME', 'postgres'),
        'password' => env('SUPABASE_DB_PASSWORD', ''),
    ],

];
