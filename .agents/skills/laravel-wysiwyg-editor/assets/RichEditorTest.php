<?php

namespace Tests\Feature;

use App\Models\Post;   // adapt: the model that holds the rich-text column
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RichEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_script_tags_are_stripped_from_body(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('posts.store'), [   // adapt route + fields
            'title' => 'Test',
            'body'  => '<p>Hello</p><script>alert(1)</script>',
        ]);

        $this->assertDatabaseHas('posts', ['body' => '<p>Hello</p>']);
    }

    public function test_image_upload_returns_url(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('editor.upload'), ['image' => UploadedFile::fake()->image('a.jpg')])
            ->assertOk()
            ->assertJsonStructure(['url']);
    }
}
