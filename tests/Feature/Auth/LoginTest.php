<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Ensure the logout route exists for testing
        Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->middleware('web')
            ->name('logout');
    }

    /**
     * Test successful login.
     */
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/authenticated', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    /**
     * Test login with invalid credentials.
     */
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/authenticated', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /**
     * Test login attempts rate limiting.
     */
    public function test_users_are_rate_limited_after_too_many_login_attempts(): void
    {
        RateLimiter::clear('login.127.0.0.1');

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Make 5 failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/authenticated', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post('/authenticated', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429); // Too many requests
    }

    /**
     * Test session regeneration after login.
     */
    public function test_session_regenerates_after_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->withSession(['test_value' => 'before']);

        $sessionId = session()->getId();

        $response = $this->post('/authenticated', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertNotEquals($sessionId, session()->getId());
    }

    /**
     * Test generic error message for non-existent accounts to prevent enumeration.
     */
    public function test_generic_error_message_for_nonexistent_account(): void
    {
        $response = $this->post('/authenticated', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHas('auth_error');
        $response->assertSessionDoesntHaveErrors(['email' => 'These credentials do not match our records.']);
        $response->assertSessionDoesntHaveErrors(['email' => 'This email is not registered.']);
    }

    /**
     * Test logout functionality.
     */
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    /**
     * Test session invalidation after logout.
     */
    public function test_session_invalidated_after_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['test_value' => 'test'])
            ->post('/logout');

        $this->assertFalse(session()->has('test_value'));
    }

    /**
     * Test CSRF token regeneration after logout.
     */
    public function test_csrf_token_regenerated_after_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $tokenBefore = csrf_token();

        $this->post('/logout');

        $tokenAfter = csrf_token();
        $this->assertNotEquals($tokenBefore, $tokenAfter);
    }
}
