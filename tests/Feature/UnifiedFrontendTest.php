<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UnifiedFrontendTest extends TestCase
{
    use RefreshDatabase;

    protected Province $province;
    protected Destination $destination;
    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->province = Province::create([
            'name' => 'Siem Reap',
            'slug' => 'siem-reap',
            'name_in_khmer' => 'សៀមរាប',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $this->destination = Destination::create([
            'province_id' => $this->province->id,
            'place_name' => 'Angkor Wat',
            'slug' => 'angkor-wat',
            'short_description' => 'Ancient temple in Siem Reap',
            'place_description' => 'Famous temple complex in Cambodia.',
            'thumbnail' => 'destinations/angkor-wat.jpg',
            'is_recommended' => true,
        ]);

        $this->user = User::factory()->create(['is_active' => true]);
        $this->user->assignRole('user');

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole('admin');
    }

    public function test_guest_can_access_unified_frontend_pages(): void
    {
        $this->get('/')->assertOk()->assertSee('Tesachor');
        $this->get('/provinces')->assertOk()->assertSee('Siem Reap');
        $this->get('/provinces/siem-reap')->assertOk()->assertSee('Angkor Wat');
        $this->get('/destinations/angkor-wat')->assertOk()->assertSee('Angkor Wat');
    }

    public function test_authenticated_user_can_access_unified_frontend_pages(): void
    {
        $this->actingAs($this->user)->get('/')->assertOk()->assertSee('Tesachor');
        $this->actingAs($this->user)->get('/dashboard')->assertOk()->assertSee('Tesachor');
        $this->actingAs($this->user)->get('/provinces')->assertOk()->assertSee('Siem Reap');
        $this->actingAs($this->user)->get('/provinces/siem-reap')->assertOk()->assertSee('Angkor Wat');
        $this->actingAs($this->user)->get('/destinations/angkor-wat')->assertOk()->assertSee('Angkor Wat');
    }

    public function test_legacy_user_dashboard_redirects_to_unified_dashboard(): void
    {
        $this->actingAs($this->user)->get('/user/dashboard')
            ->assertRedirect(route('users.dashboard'));
    }

    public function test_admin_visiting_home_is_redirected_to_admin_dashboard(): void
    {
        $this->actingAs($this->admin)->get('/')
            ->assertRedirect(route('admins.dashboard'));
    }
}
