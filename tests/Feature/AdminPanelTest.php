<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Province;
use App\Models\User;
use App\Services\DestinationService;
use App\Services\ProvinceService;
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

    public function test_store_province_fails_when_name_in_khmer_contains_non_khmer_characters(): void
    {
        $file = UploadedFile::fake()->create('kampot.jpg', 100, 'image/jpeg');

        // English characters rejected
        $response = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'Kampot',
            'name_in_khmer' => 'Kampot',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors(['name_in_khmer']);

        // Digits rejected
        $responseWithDigits = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'Kampot Two',
            'name_in_khmer' => 'កំពត 123',
            'thumbnail' => $file,
        ]);

        $responseWithDigits->assertSessionHasErrors(['name_in_khmer']);
    }

    public function test_store_province_requires_name_in_khmer(): void
    {
        $file = UploadedFile::fake()->create('kampot.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'Kampot',
            'name_in_khmer' => '',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors(['name_in_khmer']);
    }

    public function test_store_province_accepts_khmer_name_with_spaces(): void
    {
        $file = UploadedFile::fake()->create('banteay_meanchey.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'Banteay Meanchey',
            'name_in_khmer' => 'បន្ទាយ មានជ័យ',
            'thumbnail' => $file,
        ]);

        $response->assertRedirect(route('admins.provinces.index'));
        $this->assertDatabaseHas('provinces', [
            'name' => 'Banteay Meanchey',
            'name_in_khmer' => 'បន្ទាយ មានជ័យ',
            'slug' => 'banteay-meanchey',
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

    public function test_store_province_fails_when_name_already_exists(): void
    {
        Province::create([
            'name' => 'Battambang',
            'name_in_khmer' => 'បាត់ដំបង',
            'slug' => 'battambang',
            'thumbnail' => 'provinces/battambang.jpg',
        ]);

        $file = UploadedFile::fake()->create('battambang.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'Battambang',
            'name_in_khmer' => 'បាត់ដំបងថ្មី',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals('This province already exists.', session('errors')->first('name'));
    }

    public function test_update_province_fails_when_name_already_exists_on_another_province(): void
    {
        $province1 = Province::create([
            'name' => 'Kampot',
            'name_in_khmer' => 'កំពត',
            'slug' => 'kampot',
            'thumbnail' => 'provinces/kampot.jpg',
        ]);

        $province2 = Province::create([
            'name' => 'Kep',
            'name_in_khmer' => 'កែប',
            'slug' => 'kep',
            'thumbnail' => 'provinces/kep.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admins.provinces.update', $province2->slug), [
            'name' => 'Kampot',
            'name_in_khmer' => 'កែបថ្មី',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals('This province already exists.', session('errors')->first('name'));
    }

    public function test_update_province_fails_when_name_in_khmer_contains_non_khmer_characters(): void
    {
        $province = Province::create([
            'name' => 'Pursat',
            'name_in_khmer' => 'ពោធិ៍សាត់',
            'slug' => 'pursat',
            'thumbnail' => 'provinces/pursat.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admins.provinces.update', $province->slug), [
            'name' => 'Pursat Updated',
            'name_in_khmer' => 'Invalid Khmer 123',
        ]);

        $response->assertSessionHasErrors(['name_in_khmer']);
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

        $category = Category::create([
            'name' => 'Culture',
            'slug' => 'culture',
        ]);

        $destination = Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
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
        $response->assertSee('Culture');
    }

    public function test_admin_can_view_create_destination_form_with_categories(): void
    {
        Category::create(['name' => 'Temples', 'slug' => 'temples']);
        Category::create(['name' => 'Beaches', 'slug' => 'beaches']);

        $response = $this->actingAs($this->admin)->get(route('admins.destinations.create'));

        $response->assertOk();
        $response->assertSee('Create New Destination');
        $response->assertSee('Category');
        $response->assertSee('Temples');
        $response->assertSee('Beaches');
    }

    public function test_admin_can_store_destination(): void
    {
        $province = Province::create([
            'name' => 'Phnom Penh',
            'name_in_khmer' => 'ភ្នំពេញ',
            'slug' => 'phnom-penh',
            'thumbnail' => 'provinces/phnom-penh.jpg',
        ]);

        $category = Category::create([
            'name' => 'Historical',
            'slug' => 'historical',
        ]);

        $thumbnail = UploadedFile::fake()->create('palace.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.destinations.store'), [
            'place_name' => 'Royal Palace',
            'province_id' => $province->id,
            'category_id' => $category->id,
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
            'category_id' => $category->id,
        ]);
    }

    public function test_store_destination_validation_fails_without_category(): void
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
        ]);

        $response->assertSessionHasErrors(['category_id']);
    }

    public function test_store_destination_validation_fails_with_invalid_category(): void
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
            'category_id' => 99999,
            'short_description' => 'Official residence of the King of Cambodia.',
            'place_description' => 'A complex of buildings which serves as the royal residence.',
            'thumbnail' => $thumbnail,
        ]);

        $response->assertSessionHasErrors(['category_id']);
    }

    public function test_store_destination_retains_selected_category_on_validation_failure(): void
    {
        $category = Category::create([
            'name' => 'Nature',
            'slug' => 'nature',
        ]);

        $response = $this->actingAs($this->admin)->from(route('admins.destinations.create'))->post(route('admins.destinations.store'), [
            'place_name' => 'Test Mountain',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('admins.destinations.create'));
        $response->assertSessionHasErrors();
        $this->assertEquals($category->id, session('_old_input.category_id'));
    }

    public function test_admin_can_view_edit_destination_form_with_selected_category(): void
    {
        $province = Province::create([
            'name' => 'Takeo',
            'name_in_khmer' => 'តាកែវ',
            'slug' => 'takeo',
            'thumbnail' => 'provinces/takeo.jpg',
        ]);

        $category = Category::create([
            'name' => 'Temples',
            'slug' => 'temples',
        ]);

        $destination = Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'Phnom Da',
            'slug' => 'phnom-da',
            'short_description' => 'Ancient pre-Angkorian temple.',
            'place_description' => 'Temple from the Funan period.',
            'thumbnail' => 'destinations/phnom-da.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.destinations.edit', $destination->slug));

        $response->assertOk();
        $response->assertSee('Edit Destination');
        $response->assertSee('Temples');
    }

    public function test_admin_can_update_destination(): void
    {
        $province = Province::create([
            'name' => 'Takeo',
            'name_in_khmer' => 'តាកែវ',
            'slug' => 'takeo',
            'thumbnail' => 'provinces/takeo.jpg',
        ]);

        $category1 = Category::create(['name' => 'Old Category', 'slug' => 'old-category']);
        $category2 = Category::create(['name' => 'New Category', 'slug' => 'new-category']);

        $destination = Destination::create([
            'province_id' => $province->id,
            'category_id' => $category1->id,
            'place_name' => 'Phnom Da',
            'slug' => 'phnom-da',
            'short_description' => 'Ancient pre-Angkorian temple.',
            'place_description' => 'Temple from the Funan period.',
            'thumbnail' => 'destinations/phnom-da.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admins.destinations.update', $destination->slug), [
            'place_name' => 'Phnom Da Temple',
            'province_id' => $province->id,
            'category_id' => $category2->id,
            'short_description' => 'Updated short description.',
            'place_description' => 'Updated long description.',
            'rating' => 4.5,
        ]);

        $response->assertRedirect(route('admins.destinations.index'));
        $this->assertDatabaseHas('destinations', [
            'place_name' => 'Phnom Da Temple',
            'category_id' => $category2->id,
        ]);
    }

    public function test_store_destination_fails_when_place_name_already_exists(): void
    {
        $province = Province::create([
            'name' => 'Siem Reap 2',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap-2',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $category = Category::create([
            'name' => 'Heritage',
            'slug' => 'heritage',
        ]);

        Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'Angkor Wat Complex',
            'slug' => 'angkor-wat-complex',
            'short_description' => 'World wonder.',
            'place_description' => 'Detailed description of Angkor Wat.',
            'thumbnail' => 'destinations/angkor.jpg',
        ]);

        $thumbnail = UploadedFile::fake()->create('angkor2.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admins.destinations.store'), [
            'place_name' => 'Angkor Wat Complex',
            'province_id' => $province->id,
            'category_id' => $category->id,
            'short_description' => 'Duplicate destination test.',
            'place_description' => 'Another description.',
            'thumbnail' => $thumbnail,
        ]);

        $response->assertSessionHasErrors(['place_name']);
        $this->assertEquals('This destination already exists.', session('errors')->first('place_name'));
    }

    public function test_update_destination_fails_when_place_name_already_exists_on_another_destination(): void
    {
        $province = Province::create([
            'name' => 'Kampot 2',
            'name_in_khmer' => 'កំពត',
            'slug' => 'kampot-2',
            'thumbnail' => 'provinces/kampot.jpg',
        ]);

        $category = Category::create([
            'name' => 'Nature 2',
            'slug' => 'nature-2',
        ]);

        $dest1 = Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'Bokor Hill',
            'slug' => 'bokor-hill',
            'short_description' => 'Bokor mountain resort.',
            'place_description' => 'Mountain station.',
            'thumbnail' => 'destinations/bokor.jpg',
        ]);

        $dest2 = Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'Teuk Chhou',
            'slug' => 'teuk-chhou',
            'short_description' => 'Rapids in Kampot.',
            'place_description' => 'Freshwater rapids.',
            'thumbnail' => 'destinations/teuk-chhou.jpg',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admins.destinations.update', $dest2->slug), [
            'place_name' => 'Bokor Hill',
            'province_id' => $province->id,
            'category_id' => $category->id,
            'short_description' => 'Updated short description.',
            'place_description' => 'Updated description.',
        ]);

        $response->assertSessionHasErrors(['place_name']);
        $this->assertEquals('This destination already exists.', session('errors')->first('place_name'));
    }

    public function test_admin_can_filter_destinations_by_category(): void
    {
        $province = Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $templeCategory = Category::create(['name' => 'Temples', 'slug' => 'temples']);
        $beachCategory = Category::create(['name' => 'Beaches', 'slug' => 'beaches']);

        Destination::create([
            'province_id' => $province->id,
            'category_id' => $templeCategory->id,
            'place_name' => 'Angkor Wat Temple',
            'slug' => 'angkor-wat-temple',
            'short_description' => 'Great temple.',
            'place_description' => 'Temple detail.',
        ]);

        Destination::create([
            'province_id' => $province->id,
            'category_id' => $beachCategory->id,
            'place_name' => 'Koh Rong Island Beach',
            'slug' => 'koh-rong-island-beach',
            'short_description' => 'Island beach paradise.',
            'place_description' => 'Beach detail.',
        ]);

        // Filter by category_id
        $response = $this->actingAs($this->admin)->get(route('admins.destinations.index', ['category_id' => $templeCategory->id]));
        $response->assertOk();
        $response->assertSee('Angkor Wat Temple');
        $response->assertDontSee('Koh Rong Island Beach');

        // Filter by other category
        $response2 = $this->actingAs($this->admin)->get(route('admins.destinations.index', ['category_id' => $beachCategory->id]));
        $response2->assertOk();
        $response2->assertSee('Koh Rong Island Beach');
        $response2->assertDontSee('Angkor Wat Temple');
    }

    public function test_admin_can_filter_destinations_by_category_and_search_text(): void
    {
        $province = Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $natureCategory = Category::create(['name' => 'Nature', 'slug' => 'nature']);

        Destination::create([
            'province_id' => $province->id,
            'category_id' => $natureCategory->id,
            'place_name' => 'Kulen Waterfall',
            'slug' => 'kulen-waterfall',
            'short_description' => 'Lush waterfalls.',
            'place_description' => 'Waterfall detail.',
        ]);

        Destination::create([
            'province_id' => $province->id,
            'category_id' => $natureCategory->id,
            'place_name' => 'Tonle Sap Biosphere',
            'slug' => 'tonle-sap-biosphere',
            'short_description' => 'Lake ecosystem.',
            'place_description' => 'Lake detail.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.destinations.index', [
            'category_id' => $natureCategory->id,
            'search' => 'Waterfall',
        ]));

        $response->assertOk();
        $response->assertSee('Kulen Waterfall');
        $response->assertDontSee('Tonle Sap Biosphere');
    }

    public function test_admin_can_filter_destinations_by_category_and_province(): void
    {
        $province1 = Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $province2 = Province::create([
            'name' => 'Kampot',
            'name_in_khmer' => 'កំពត',
            'slug' => 'kampot',
            'thumbnail' => 'provinces/kampot.jpg',
        ]);

        $natureCategory = Category::create(['name' => 'Nature', 'slug' => 'nature']);

        Destination::create([
            'province_id' => $province1->id,
            'category_id' => $natureCategory->id,
            'place_name' => 'Siem Reap Nature Spot',
            'slug' => 'siem-reap-nature-spot',
            'short_description' => 'Spot in Siem Reap.',
            'place_description' => 'Description.',
        ]);

        Destination::create([
            'province_id' => $province2->id,
            'category_id' => $natureCategory->id,
            'place_name' => 'Bokor National Park',
            'slug' => 'bokor-national-park',
            'short_description' => 'Park in Kampot.',
            'place_description' => 'Description.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admins.destinations.index', [
            'category_id' => $natureCategory->id,
            'province_id' => $province2->id,
        ]));

        $response->assertOk();
        $response->assertSee('Bokor National Park');
        $response->assertDontSee('Siem Reap Nature Spot');
    }

    public function test_category_filter_retains_query_string_in_pagination(): void
    {
        $province = Province::create([
            'name' => 'Battambang',
            'name_in_khmer' => 'បាត់ដំបង',
            'slug' => 'battambang',
            'thumbnail' => 'provinces/battambang.jpg',
        ]);

        $category = Category::create(['name' => 'Temples', 'slug' => 'temples']);

        for ($i = 1; $i <= 16; $i++) {
            Destination::create([
                'province_id' => $province->id,
                'category_id' => $category->id,
                'place_name' => "Pagoda {$i}",
                'slug' => "pagoda-{$i}",
                'short_description' => "Description {$i}",
                'place_description' => "Full description {$i}",
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('admins.destinations.index', [
            'category_id' => $category->id,
        ]));

        $response->assertOk();
        $response->assertSee('category_id='.$category->id);
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

    public function test_admin_dashboard_renders_profile_edit_link_and_logout_modal(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admins.dashboard'));

        $response->assertOk();
        $response->assertSee('Settings');
        $response->assertSee(route('admins.profile.edit'));
        $response->assertSee('Sign Out Confirmation');
        $response->assertSee('Cancel');
    }

    public function test_admin_can_access_and_update_profile_from_admin_route(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admins.profile.edit'));
        $response->assertOk();
        $response->assertSee('Admin Profile Settings');
        $response->assertSee('Profile Overview');
        $response->assertSee('Profile Preferences');
        $response->assertSee('Change Password');
        $response->assertDontSee('Delete Account');
        $response->assertSee(route('profile.avatar.update'));

        $updateResponse = $this->actingAs($this->admin)->patch(route('admins.profile.update'), [
            'name' => 'Updated Admin Name',
            'email' => 'updatedadmin@example.com',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('admins.profile.edit'));
        $this->assertSame('Updated Admin Name', $this->admin->fresh()->name);
        $this->assertSame('updatedadmin@example.com', $this->admin->fresh()->email);
    }

    public function test_admin_can_update_password_from_profile_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new-secret-password123',
            'password_confirmation' => 'new-secret-password123',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status', 'password-updated');
    }

    public function test_unique_slug_appends_numerical_suffix_when_slug_exists(): void
    {
        Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => null,
        ]);

        $provinceService = app(ProvinceService::class);
        $this->assertSame('siem-reap-2', $provinceService->uniqueSlug('Siem Reap'));

        Province::create([
            'name' => 'Siem Reap 2',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap-2',
            'thumbnail' => null,
        ]);

        $this->assertSame('siem-reap-3', $provinceService->uniqueSlug('Siem Reap'));

        $category = Category::create(['name' => 'Temples', 'slug' => 'temples']);
        Destination::create([
            'province_id' => Province::first()->id,
            'category_id' => $category->id,
            'place_name' => 'Angkor Wat',
            'slug' => 'angkor-wat',
            'short_description' => 'Historic temple',
            'place_description' => 'Historic temple description',
            'thumbnail' => 'test.jpg',
        ]);

        $destinationService = app(DestinationService::class);
        $this->assertSame('angkor-wat-2', $destinationService->uniqueSlug('Angkor Wat'));
    }

    public function test_store_province_and_destination_rejects_duplicate_name_with_different_casing(): void
    {
        $file = UploadedFile::fake()->create('battambang.jpg', 100, 'image/jpeg');

        Province::create([
            'name' => 'Battambang',
            'name_in_khmer' => 'បាត់ដំបង',
            'slug' => 'battambang',
            'thumbnail' => 'thumbnails/1/test.jpg',
        ]);

        // Lowercase variant fails validation
        $response = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'battambang',
            'name_in_khmer' => 'បាត់ដំបង',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors(['name' => 'This province already exists.']);

        // Uppercase variant fails validation
        $responseUpper = $this->actingAs($this->admin)->post(route('admins.provinces.store'), [
            'name' => 'BATTAMBANG',
            'name_in_khmer' => 'បាត់ដំបង',
            'thumbnail' => $file,
        ]);

        $responseUpper->assertSessionHasErrors(['name' => 'This province already exists.']);

        $category = Category::create(['name' => 'Nature', 'slug' => 'nature']);
        $province = Province::first();

        Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'Phnom Kulen',
            'slug' => 'phnom-kulen',
            'short_description' => 'Sacred mountain',
            'place_description' => 'Sacred mountain description',
            'thumbnail' => 'test.jpg',
        ]);

        // Destination with different casing fails validation
        $destResponse = $this->actingAs($this->admin)->post(route('admins.destinations.store'), [
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'phnom kulen',
            'short_description' => 'Another description',
            'place_description' => 'Another full description',
            'thumbnail' => $file,
        ]);

        $destResponse->assertSessionHasErrors(['place_name' => 'This destination already exists.']);
    }

    public function test_unique_slug_falls_back_to_default_when_slug_is_empty(): void
    {
        $provinceService = app(ProvinceService::class);
        $this->assertSame('province', $provinceService->uniqueSlug('???'));

        Province::create([
            'name' => '???',
            'name_in_khmer' => 'ទូទៅ',
            'slug' => 'province',
            'thumbnail' => null,
        ]);

        $this->assertSame('province-2', $provinceService->uniqueSlug('???'));

        $destinationService = app(DestinationService::class);
        $this->assertSame('destination', $destinationService->uniqueSlug('---'));

        $category = Category::create(['name' => 'Adventure', 'slug' => 'adventure']);
        $province = Province::first();
        Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => '---',
            'slug' => 'destination',
            'short_description' => 'Desc',
            'place_description' => 'Desc full',
            'thumbnail' => 'test.jpg',
        ]);

        $this->assertSame('destination-2', $destinationService->uniqueSlug('---'));
    }

    public function test_failed_thumbnail_upload_leaves_no_orphan_province_or_destination_row(): void
    {
        Storage::shouldReceive('disk')
            ->with('supabase_province')
            ->andReturnSelf();
        Storage::shouldReceive('putFile')
            ->andThrow(new \RuntimeException('Supabase disk upload connection timed out'));

        $provinceService = app(ProvinceService::class);
        $file = UploadedFile::fake()->create('failed.jpg', 100, 'image/jpeg');

        try {
            $provinceService->createProvince([
                'name' => 'Kratie',
                'name_in_khmer' => 'ក្រចេះ',
            ], $file);
            $this->fail('Expected exception was not thrown');
        } catch (\RuntimeException $e) {
            $this->assertSame('Supabase disk upload connection timed out', $e->getMessage());
        }

        $this->assertDatabaseMissing('provinces', ['name' => 'Kratie']);
        $this->assertSame(0, Province::where('name', 'Kratie')->count());
    }

    public function test_admin_views_render_reusable_delete_confirm_modal_and_triggers(): void
    {
        $province = Province::create([
            'name' => 'Koh Kong',
            'name_in_khmer' => 'កោះកុង',
            'slug' => 'koh-kong',
            'thumbnail' => 'provinces/koh-kong.jpg',
        ]);

        $category = Category::create(['name' => 'Islands', 'slug' => 'islands']);
        $destination = Destination::create([
            'province_id' => $province->id,
            'category_id' => $category->id,
            'place_name' => 'Koh Rong Sanloem',
            'slug' => 'koh-rong-sanloem',
            'short_description' => 'Beautiful island beach',
            'place_description' => 'Detailed description of Koh Rong Sanloem island.',
            'thumbnail' => 'destinations/koh-rong-sanloem.jpg',
        ]);

        // 1. Province index
        $provinceIndex = $this->actingAs($this->admin)->get(route('admins.provinces.index'));
        $provinceIndex->assertOk();
        $provinceIndex->assertSee('deleteConfirmModal');
        $provinceIndex->assertSee('data-confirm-delete', false);
        $provinceIndex->assertDontSee('onsubmit="return confirm', false);
        $provinceIndex->assertSee('This will also permanently delete all of its associated destinations and media.');

        // 2. Province show
        $provinceShow = $this->actingAs($this->admin)->get(route('admins.provinces.show', $province->slug));
        $provinceShow->assertOk();
        $provinceShow->assertSee('data-confirm-delete', false);
        $provinceShow->assertDontSee('onsubmit="return confirm', false);

        // 3. Destination index
        $destIndex = $this->actingAs($this->admin)->get(route('admins.destinations.index'));
        $destIndex->assertOk();
        $destIndex->assertSee('deleteConfirmModal');
        $destIndex->assertSee('data-confirm-delete', false);
        $destIndex->assertDontSee('onsubmit="return confirm', false);
        $destIndex->assertSee('This action cannot be undone.');

        // 4. Destination show
        $destShow = $this->actingAs($this->admin)->get(route('admins.destinations.show', $destination->slug));
        $destShow->assertOk();
        $destShow->assertSee('data-confirm-delete', false);
        $destShow->assertDontSee('onsubmit="return confirm', false);
    }
}
