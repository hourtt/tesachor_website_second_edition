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

            // Delete all existing admin accounts
            $existingAdmins = User::role('admin')->get();

            foreach ($existingAdmins as $user) {
                $user->syncRoles([]);
                $user->delete();
            }

            $adminAccount = $existingAdmins->count();
            $this->command->info(
                "{$adminAccount} admin" . ($adminAccount === 1 ? " account" : " accounts") . " has been deleted."
            );

            // Create a new admin account
            $admin = User::create([
                'name' => 'System Administrator',
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $admin->assignRole($adminRole);

            $this->command->info(
                "New admin account created: {$email}"
            );
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
