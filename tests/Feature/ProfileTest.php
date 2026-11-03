<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_public_profile_route_returns_404(): void
    {
        $user = User::factory()->create();

        $this->get('/profile')->assertNotFound();

        $this->actingAs($user)
            ->get('/profile')
            ->assertNotFound();
    }
}
