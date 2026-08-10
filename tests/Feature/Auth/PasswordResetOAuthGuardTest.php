<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetOAuthGuardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Akun yang terdaftar via Google dan belum pernah menetapkan password
     * tidak boleh meminta reset link — guard harus menolak tanpa membuat token.
     */
    public function test_oauth_only_user_cannot_request_password_reset_link(): void
    {
        $user = User::factory()->create([
            'google_id' => 'google-123',
            'password' => Hash::make('random-oauth-stub'),
        ]);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    /**
     * Pengguna yang terdaftar dengan email/password tetap bisa meminta reset link.
     */
    public function test_registered_user_can_request_password_reset_link(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
            'password_set_at' => now(),
        ]);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    /**
     * Akun Google yang sudah menetapkan password (dual-auth) tetap boleh
     * meminta reset link.
     */
    public function test_oauth_user_with_chosen_password_can_request_reset_link(): void
    {
        $user = User::factory()->create([
            'google_id' => 'google-123',
            'password' => Hash::make('secret123'),
            'password_set_at' => now(),
        ]);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }
}
