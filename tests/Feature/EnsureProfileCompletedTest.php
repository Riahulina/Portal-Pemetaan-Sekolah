<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureProfileCompletedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_phone_is_redirected_to_profile(): void
    {
        $user = User::factory()->create(['phone_number' => null]);

        $response = $this->actingAs($user)->get('/user/dashboard');

        $response->assertRedirect(route('profile.user'));
        $response->assertSessionHas('warning');
    }

    public function test_user_with_phone_can_access_dashboard(): void
    {
        $user = User::factory()->create(['phone_number' => '81234567890']);

        $response = $this->actingAs($user)->get('/user/dashboard');

        $response->assertOk();
    }

    public function test_admin_without_phone_is_not_redirected(): void
    {
        $user = User::factory()->create([
            'phone_number' => null,
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->get('/user/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }
}
