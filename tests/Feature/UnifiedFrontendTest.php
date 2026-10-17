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
        $this->get(route('public.landing'))->assertOk()->assertSee('Tesachor');
        $this->get(route('public.provinces.index'))->assertOk()->assertSee('Siem Reap');
        $this->get(route('public.provinces.show', 'siem-reap'))->assertOk()->assertSee('Angkor Wat');
        $this->get(route('public.destinations.show', 'angkor-wat'))->assertOk()->assertSee('Angkor Wat');
    }

    public function test_authenticated_user_can_access_unified_frontend_pages(): void
    {
        $this->actingAs($this->user)->get(route('public.landing'))->assertOk()->assertSee('Tesachor');
        $this->actingAs($this->user)->get(route('public.provinces.index'))->assertOk()->assertSee('Siem Reap');
        $this->actingAs($this->user)->get(route('public.provinces.show', 'siem-reap'))->assertOk()->assertSee('Angkor Wat');
        $this->actingAs($this->user)->get(route('public.destinations.show', 'angkor-wat'))->assertOk()->assertSee('Angkor Wat');
    }

    public function test_admin_visiting_home_is_redirected_to_admin_dashboard(): void
    {
        $this->actingAs($this->admin)->get(route('public.landing'))
            ->assertRedirect(route('admins.dashboard'));
    }
}
