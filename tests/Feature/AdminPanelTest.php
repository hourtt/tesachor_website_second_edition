<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->regularUser = User::factory()->create();
        $this->regularUser->assignRole('user');

        Storage::fake('public');
    }

    // 1. RBAC & DASHBOARD TESTS

    public function test_admin_can_view_dashboard_with_statistics(): void
    {
        $province = Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        Destination::create([
            'province_id' => $province->id,
            'place_name' => 'Angkor Wat',
            'slug' => 'angkor-wat',
            'short_description' => 'Famous temple complex',
            'place_description' => 'A monumental ancient temple in Cambodia.',
            'thumbnail' => 'destinations/angkor-wat.jpg',
            'rating' => 4.9,
            'is_recommended' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.dashboard'));

        $response->assertOk();
        $response->assertSee('Welcome back');
        $response->assertSee('Angkor Wat');
        $response->assertSee('Siem Reap');
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admins.dashboard'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admins.dashboard'));

        $response->assertRedirect(route('login'));
    }

    // 2. PROVINCES CRUD TESTS

    public function test_admin_can_view_provinces_index(): void
    {
        $province = Province::create([
            'name' => 'Battambang',
            'name_in_khmer' => 'បាត់ដំបង',
            'slug' => 'battambang',
            'thumbnail' => 'provinces/battambang.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.provinces.index'));

        $response->assertOk();
        $response->assertSee('Battambang');
    }

    public function test_admin_can_view_create_province_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admins.provinces.create'));

        $response->assertOk();
        $response->assertSee('Add New Province');
    }

    public function test_admin_can_store_province(): void
    {
        $file = UploadedFile::fake()->create('kampot.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'Kampot',
            'name_in_khmer' => 'កំពត',
            'thumbnail' => $file,
        ]);

        $response->assertRedirect(route('admins.provinces.index'));
        $this->assertDatabaseHas('provinces', [
            'name' => 'Kampot',
            'name_in_khmer' => 'កំពត',
            'slug' => 'kampot',
        ]);
    }

    public function test_admin_can_view_province_show(): void
    {
        $province = Province::create([
            'name' => 'Kep',
            'name_in_khmer' => 'កែប',
            'slug' => 'kep',
            'thumbnail' => 'provinces/kep.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.provinces.show', $province->slug));

        $response->assertOk();
        $response->assertSee('Kep');
        $response->assertSee('កែប');
    }

    public function test_admin_can_view_province_edit_form(): void
    {
        $province = Province::create([
            'name' => 'Koh Kong',
            'name_in_khmer' => 'កោះកុង',
            'slug' => 'koh-kong',
            'thumbnail' => 'provinces/koh-kong.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.provinces.edit', $province->slug));

        $response->assertOk();
        $response->assertSee('Koh Kong');
    }

    public function test_admin_can_update_province(): void
    {
        $province = Province::create([
            'name' => 'Pursat',
            'name_in_khmer' => 'ពោធិ៍សាត់',
            'slug' => 'pursat',
            'thumbnail' => 'provinces/pursat.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admins.provinces.update', $province->slug), [
            'name' => 'Pursat Updated',
            'name_in_khmer' => 'ពោធិ៍សាត់ថ្មី',
        ]);

        $response->assertRedirect(route('admins.provinces.index'));
        $this->assertDatabaseHas('provinces', [
            'name' => 'Pursat Updated',
            'name_in_khmer' => 'ពោធិ៍សាត់ថ្មី',
        ]);
    }

    public function test_admin_can_delete_province_without_destinations(): void
    {
        $province = Province::create([
            'name' => 'Pailin',
            'name_in_khmer' => 'ប៉ៃលិន',
            'slug' => 'pailin',
            'thumbnail' => 'provinces/pailin.jpg',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admins.provinces.destroy', $province->slug));

        $response->assertRedirect(route('admins.provinces.index'));
        $this->assertDatabaseMissing('provinces', ['slug' => 'pailin']);
    }

    // 3. DESTINATIONS CRUD TESTS

    public function test_admin_can_view_destinations_index(): void
    {
        $province = Province::create([
            'name' => 'Kandal',
            'name_in_khmer' => 'កណ្តាល',
            'slug' => 'kandal',
            'thumbnail' => 'provinces/kandal.jpg',
        ]);

        $destination = Destination::create([
            'province_id' => $province->id,
            'place_name' => 'Oudong Mountain',
            'slug' => 'oudong-mountain',
            'short_description' => 'Historical royal necropolis.',
            'place_description' => 'A famous historical mountain shrine.',
            'thumbnail' => 'destinations/oudong.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.destinations.index'));

        $response->assertOk();
        $response->assertSee('Oudong Mountain');
        $response->assertSee('Kandal');
    }

    public function test_admin_can_store_destination(): void
    {
        $province = Province::create([
            'name' => 'Phnom Penh',
            'name_in_khmer' => 'ភ្នំពេញ',
            'slug' => 'phnom-penh',
            'thumbnail' => 'provinces/phnom-penh.jpg',
        ]);

        $thumbnail = UploadedFile::fake()->create('palace.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.destinations.store'), [
            'place_name' => 'Royal Palace',
            'province_id' => $province->id,
            'short_description' => 'Official residence of the King of Cambodia.',
            'place_description' => 'A complex of buildings which serves as the royal residence.',
            'thumbnail' => $thumbnail,
            'rating' => 4.8,
            'is_recommended' => 1,
            'highlights' => ['Throne Hall', 'Silver Pagoda'],
        ]);

        $response->assertRedirect(route('admins.provinces.show', $province->slug));
        $this->assertDatabaseHas('destinations', [
            'place_name' => 'Royal Palace',
            'slug' => 'royal-palace',
        ]);
    }

    public function test_admin_can_update_destination(): void
    {
        $province = Province::create([
            'name' => 'Takeo',
            'name_in_khmer' => 'តាកែវ',
            'slug' => 'takeo',
            'thumbnail' => 'provinces/takeo.jpg',
        ]);

        $destination = Destination::create([
            'province_id' => $province->id,
            'place_name' => 'Phnom Da',
            'slug' => 'phnom-da',
            'short_description' => 'Ancient pre-Angkorian temple.',
            'place_description' => 'Temple from the Funan period.',
            'thumbnail' => 'destinations/phnom-da.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admins.destinations.update', $destination->slug), [
            'place_name' => 'Phnom Da Temple',
            'province_id' => $province->id,
            'short_description' => 'Updated short description.',
            'place_description' => 'Updated long description.',
            'rating' => 4.5,
        ]);

        $response->assertRedirect(route('admins.destinations.index'));
        $this->assertDatabaseHas('destinations', [
            'place_name' => 'Phnom Da Temple',
        ]);
    }

    public function test_admin_can_delete_destination(): void
    {
        $province = Province::create([
            'name' => 'Preah Vihear',
            'name_in_khmer' => 'ព្រះវិហារ',
            'slug' => 'preah-vihear',
            'thumbnail' => 'provinces/preah-vihear.jpg',
        ]);

        $destination = Destination::create([
            'province_id' => $province->id,
            'place_name' => 'Preah Vihear Temple',
            'slug' => 'preah-vihear-temple',
            'short_description' => 'Cliffside temple UNESCO heritage site.',
            'place_description' => 'Ancient temple located atop a cliff.',
            'thumbnail' => 'destinations/preah-vihear.jpg',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admins.destinations.destroy', $destination->slug));

        $response->assertRedirect();
        $this->assertDatabaseMissing('destinations', ['slug' => 'preah-vihear-temple']);
    }

    // 4. USERS MANAGEMENT & DEACTIVATION TESTS

    public function test_admin_can_view_users_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admins.users.index'));

        $response->assertOk();
        $response->assertSee($this->admin->name);
        $response->assertSee($this->regularUser->name);
    }

    public function test_admin_can_update_user_role_and_status(): void
    {
        $targetUser = User::factory()->create();
        $targetUser->assignRole('user');

        $response = $this->actingAs($this->admin)->put(route('admins.users.update', $targetUser->id), [
            'role' => 'admin',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admins.users.index'));
        $this->assertTrue($targetUser->fresh()->hasRole('admin'));
    }

    public function test_admin_cannot_deactivate_themselves(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admins.users.update', $this->admin->id), [
            'role' => 'user',
            'is_active' => '0',
        ]);

        $response->assertSessionHas('error');
        $this->assertTrue($this->admin->fresh()->hasRole('admin'));
        $this->assertTrue($this->admin->fresh()->is_active);
    }

    public function test_deactivated_user_is_logged_out_by_middleware(): void
    {
        $activeUser = User::factory()->create(['is_active' => true]);
        $activeUser->assignRole('user');

        // Can access landing page while active
        $response = $this->actingAs($activeUser)->get(route('public.landing'));
        $response->assertOk();

        // Admin deactivates user
        $activeUser->update(['is_active' => false]);

        // Attempting to visit landing page now redirects to login
        $response2 = $this->actingAs($activeUser)->get(route('public.landing'));
        $response2->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
