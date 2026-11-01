<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $count Number of fake users to seed (default: 100)
     */
    public function run(int $count = 100): void
    {
        $role = Role::firstOrCreate(['name' => 'user']);
        $prehashedPassword = Hash::make('password123');
        $now = now();

        $this->command?->info("Generating {$count} fake users...");

        DB::transaction(function () use ($count, $prehashedPassword, $now, $role) {
            $users = [];
            for ($i = 1; $i <= $count; $i++) {
                $users[] = [
                    'name' => fake()->name(),
                    'email' => 'fake_user_' . $i . '_' . fake()->unique()->numberBetween(10000, 99999) . '@fake.tesachor.test',
                    'email_verified_at' => $now,
                    'password' => $prehashedPassword,
                    'is_active' => true,
                    'created_at' => $now->copy()->subMinutes(rand(1, 10000)),
                    'updated_at' => $now,
                ];
            }

            // Batch insert users into PostgreSQL
            User::insert($users);

            // Fetch newly created users by domain to assign roles in bulk
            $newUsers = User::where('email', 'like', '%@fake.tesachor.test')->get();
            $roleAssignments = [];

            foreach ($newUsers as $user) {
                $roleAssignments[] = [
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                ];
            }

            // Insert into model_has_roles table
            DB::table('model_has_roles')->insertOrIgnore($roleAssignments);
        });

        $this->command?->info("Successfully seeded {$count} fake users with 'user' role.");
    }
}

