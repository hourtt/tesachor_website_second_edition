<?php

namespace Tests\Feature;

use App\Models\Category;
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

    protected Destination $beachDestination;

    protected Category $templeCategory;

    protected Category $beachCategory;

    protected User $user;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->templeCategory = Category::create([
            'name' => 'Temples',
            'slug' => 'temples',
            'description' => 'Ancient temples',
        ]);

        $this->beachCategory = Category::create([
            'name' => 'Beaches',
            'slug' => 'beaches',
            'description' => 'Coastal beaches',
        ]);

        $this->province = Province::create([
            'name' => 'Siem Reap',
            'slug' => 'siem-reap',
            'name_in_khmer' => 'សៀមរាប',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $shvProvince = Province::create([
            'name' => 'Preah Sihanouk',
            'slug' => 'preah-sihanouk',
            'name_in_khmer' => 'ព្រះសីហនុ',
            'thumbnail' => 'provinces/sihanouk.jpg',
        ]);

        $this->destination = Destination::create([
            'province_id' => $this->province->id,
            'category_id' => $this->templeCategory->id,
            'place_name' => 'Angkor Wat',
            'slug' => 'angkor-wat',
            'short_description' => 'Ancient temple in Siem Reap',
            'place_description' => 'Famous temple complex in Cambodia.',
            'thumbnail' => 'destinations/angkor-wat.jpg',
            'is_recommended' => true,
        ]);

        $this->beachDestination = Destination::create([
            'province_id' => $shvProvince->id,
            'category_id' => $this->beachCategory->id,
            'place_name' => 'Ocherteal Beach',
            'slug' => 'ocherteal-beach',
            'short_description' => 'Scenic white sand beach in Sihanoukville',
            'place_description' => 'Popular beach destination.',
            'thumbnail' => 'destinations/ocherteal-beach.jpg',
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

    public function test_landing_page_displays_all_recommended_destinations_by_default(): void
    {
        $response = $this->get(route('public.landing'));

        $response->assertOk()
            ->assertSee('Angkor Wat')
            ->assertSee('Ocherteal Beach');
    }

    public function test_landing_page_filters_destinations_by_category_slug(): void
    {
        $response = $this->get(route('public.landing', ['category' => 'beaches']));

        $response->assertOk()
            ->assertSee('Ocherteal Beach')
            ->assertDontSee('Angkor Wat');
    }

    public function test_landing_page_filters_destinations_by_category_id(): void
    {
        $response = $this->get(route('public.landing', ['category' => $this->templeCategory->id]));

        $response->assertOk()
            ->assertSee('Angkor Wat')
            ->assertDontSee('Ocherteal Beach');
    }

    public function test_landing_page_filters_destinations_by_search_keyword(): void
    {
        $response = $this->get(route('public.landing', ['search' => 'Ocherteal']));

        $response->assertOk()
            ->assertSee('Ocherteal Beach')
            ->assertDontSee('Angkor Wat');
    }

    public function test_landing_page_filters_destinations_by_search_and_category(): void
    {
        $response = $this->get(route('public.landing', [
            'search' => 'Beach',
            'category' => 'beaches',
        ]));

        $response->assertOk()
            ->assertSee('Ocherteal Beach')
            ->assertDontSee('Angkor Wat');
    }

    public function test_landing_page_displays_empty_state_when_no_destinations_match(): void
    {
        $response = $this->get(route('public.landing', ['search' => 'NonExistentPlace']));

        $response->assertOk()
            ->assertDontSee('Angkor Wat')
            ->assertDontSee('Ocherteal Beach');
    }

    public function test_landing_page_renders_active_filter_pills_with_remove_links_and_clear_all(): void
    {
        $response = $this->get(route('public.landing', [
            'search' => 'Beach',
            'category' => 'beaches',
        ]));

        $response->assertOk();
        // Pills show Category and Search
        $response->assertSee('Category:');
        $response->assertSee('Beaches');
        $response->assertSee('Search:');
        $response->assertSee('"Beach"', false);
        $response->assertSee('Clear All');

        // Removing category pill link keeps search
        $removeCategoryUrl = route('public.landing', ['search' => 'Beach']) . '#travel';
        $response->assertSee($removeCategoryUrl, false);

        // Removing search pill link keeps category
        $removeSearchUrl = route('public.landing', ['category' => 'beaches']) . '#travel';
        $response->assertSee($removeSearchUrl, false);

        // Clear All link points to base landing
        $clearAllUrl = route('public.landing') . '#travel';
        $response->assertSee($clearAllUrl, false);
    }
}
