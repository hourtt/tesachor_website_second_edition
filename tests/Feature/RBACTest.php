<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admins.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_admin_receives_403_on_admin_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Attempt to access an admin-only route
        $response = $this->actingAs($user)->get(route('admins.dashboard'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_routes()
    {
        $response = $this->get(route('admins.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
