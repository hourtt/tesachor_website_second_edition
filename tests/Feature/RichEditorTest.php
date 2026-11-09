<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RichEditorTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Province $province;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->province = Province::create([
            'name' => 'Siem Reap',
            'name_in_khmer' => 'សៀមរាប',
            'slug' => 'siem-reap',
            'thumbnail' => 'provinces/siem-reap.jpg',
        ]);

        $this->category = Category::create([
            'name' => 'Historical Sites',
            'slug' => 'historical-sites',
        ]);

        Storage::fake('public');
    }

    public function test_script_tags_are_stripped_from_rich_text_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admins.destinations.store'), [
            'province_id' => $this->province->id,
            'category_id' => $this->category->id,
            'place_name' => 'Angkor Wat Test',
            'place_name_in_khmer' => 'ប្រាសាទអង្គរវត្ត',
            'short_description' => '<p>Short description</p><script>alert("xss")</script>',
            'short_description_in_khmer' => '<p>ការពិពណ៌នាសង្ខេប</p><script>alert("xss")</script>',
            'place_description' => '<p>Full description with <strong>bold</strong></p><script>alert("xss")</script>',
            'place_description_in_khmer' => '<p>ការពិពណ៌នាពេញលេញ</p><script>alert("xss")</script>',
            'place_history' => '<h2>Ancient History</h2><script>alert("xss")</script>',
            'place_history_in_khmer' => '<h2>ប្រវត្តិសាស្រ្ត</h2><script>alert("xss")</script>',
            'place_information' => '<p>Ticket info</p><script>alert("xss")</script>',
            'place_information_in_khmer' => '<p>ព័ត៌មានសំបុត្រ</p><script>alert("xss")</script>',
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ]);

        $response->assertRedirect(route('admins.provinces.show', $this->province->slug));

        $destination = Destination::where('place_name', 'Angkor Wat Test')->first();
        $this->assertNotNull($destination);

        // Assert script tags are completely stripped
        $this->assertStringNotContainsString('<script>', $destination->short_description);
        $this->assertStringNotContainsString('<script>', $destination->short_description_in_khmer);
        $this->assertStringNotContainsString('<script>', $destination->place_description);
        $this->assertStringNotContainsString('<script>', $destination->place_description_in_khmer);
        $this->assertStringNotContainsString('<script>', $destination->place_history);
        $this->assertStringNotContainsString('<script>', $destination->place_history_in_khmer);
        $this->assertStringNotContainsString('<script>', $destination->place_information);
        $this->assertStringNotContainsString('<script>', $destination->place_information_in_khmer);

        // Assert safe HTML remains
        $this->assertStringContainsString('<p>Full description with <strong>bold</strong></p>', $destination->place_description);
        $this->assertStringContainsString('<h2>Ancient History</h2>', $destination->place_history);
        $this->assertStringContainsString('<h2>ប្រវត្តិសាស្រ្ត</h2>', $destination->place_history_in_khmer);
    }

    public function test_image_upload_returns_url_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('editor.upload'), [
                'image' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertOk()
            ->assertJsonStructure(['url']);
    }

    public function test_image_upload_fails_for_unauthenticated_guests(): void
    {
        $this->postJson(route('editor.upload'), [
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ])->assertStatus(401);
    }
}
