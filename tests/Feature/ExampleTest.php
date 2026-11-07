<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_nonexistent_url_renders_custom_404_page_with_image(): void
    {
        $response = $this->get('/nonexistent-page-url-404-check');

        $response->assertStatus(404);
        $response->assertSee('storage/404.png');
    }
}
