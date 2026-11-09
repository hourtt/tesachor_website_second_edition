<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DestinationLocationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected Province $province;
    protected Category $category;
    protected Destination $destination;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->regularUser = User::factory()->create();
        $this->regularUser->assignRole('user');

        $this->province = Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $this->category = Category::create([
            'name' => 'Temples',
            'slug' => 'temples',
        ]);

        $this->destination = Destination::create([
            'province_id' => $this->province->id,
            'category_id' => $this->category->id,
            'place_name' => 'Angkor Wat',
            'place_name_in_khmer' => 'ប្រាសាទអង្គរវត្ត',
            'slug' => 'angkor-wat',
            'short_description' => 'Famous ancient temple in Cambodia.',
            'thumbnail' => 'destinations/angkor-wat.jpg',
            'latitude' => null,
            'longitude' => null,
        ]);
    }

    public function test_admin_can_update_destination_location(): void
    {
        $response = $this->actingAs($this->admin)
            ->patchJson(route('admins.destinations.location.update', $this->destination->id), [
                'latitude' => 13.4124693,
                'longitude' => 103.8669857,
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Location updated successfully.',
            ]);

        $this->destination->refresh();
        $this->assertEquals(13.4124693, $this->destination->latitude);
        $this->assertEquals(103.8669857, $this->destination->longitude);
    }

    public function test_guest_cannot_update_destination_location(): void
    {
        $response = $this->patchJson(route('admins.destinations.location.update', $this->destination->id), [
            'latitude' => 13.4124693,
            'longitude' => 103.8669857,
        ]);

        // Stealth 404 or 401/403 for unauthorized guests
        $this->assertTrue(in_array($response->status(), [401, 403, 404]));

        $this->destination->refresh();
        $this->assertNull($this->destination->latitude);
        $this->assertNull($this->destination->longitude);
    }

    public function test_non_admin_cannot_update_destination_location(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->patchJson(route('admins.destinations.location.update', $this->destination->id), [
                'latitude' => 13.4124693,
                'longitude' => 103.8669857,
            ]);

        // Non-admin receives stealth 404 or 403 on admin routes
        $this->assertTrue(in_array($response->status(), [403, 404]));

        $this->destination->refresh();
        $this->assertNull($this->destination->latitude);
        $this->assertNull($this->destination->longitude);
    }

    public function test_invalid_latitude_fails_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->patchJson(route('admins.destinations.location.update', $this->destination->id), [
                'latitude' => 95.0, // Exceeds 90
                'longitude' => 103.8669857,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['latitude']);
    }

    public function test_invalid_longitude_fails_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->patchJson(route('admins.destinations.location.update', $this->destination->id), [
                'latitude' => 13.4124693,
                'longitude' => 195.0, // Exceeds 180
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['longitude']);
    }

    public function test_non_numeric_coordinates_fail_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->patchJson(route('admins.destinations.location.update', $this->destination->id), [
                'latitude' => 'not-a-number',
                'longitude' => 'invalid-coord',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['latitude', 'longitude']);
    }

    public function test_admin_show_page_renders_editable_map_component(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admins.destinations.show', $this->destination->id));

        $response->assertOk();
        $response->assertSee('Location');
        $response->assertSee('destinationMap');
        $response->assertSee('Search place or address to relocate...');
        $response->assertSee('Save location');
    }

    public function test_public_show_page_renders_read_only_map_without_admin_controls(): void
    {
        $this->destination->update([
            'latitude' => 13.4124693,
            'longitude' => 103.8669857,
        ]);

        $response = $this->get(route('public.destinations.show', $this->destination->slug));

        $response->assertOk();
        $response->assertSee('Location');
        $response->assertSee('destinationMap');
        $response->assertSee('Open in Google Maps');

        // Admin controls must NEVER be present on the public page
        $response->assertDontSee('Search place or address to relocate...');
        $response->assertDontSee('Save location');
        $response->assertDontSee(route('admins.destinations.location.update', $this->destination->id));
    }
}

