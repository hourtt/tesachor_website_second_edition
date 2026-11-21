<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () {
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $email = config('app.admin_email')
                ?? env('ADMIN_EMAIL', 'admin@example.com');

            $password = config('app.admin_password')
                ?? env('ADMIN_PASSWORD');

            if (empty($password)) {
                throw new \RuntimeException(
                    'ADMIN_PASSWORD is not configured.'
                );
            }

            // Ensure admin account exists without deleting other administrators
            $admin = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'System Administrator',
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]
            );

            if (! $admin->hasRole('admin')) {
                $admin->assignRole($adminRole);
            }

            $this->command->info(
                "Admin account ensured: {$email}"
            );
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
