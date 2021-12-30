<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_it_blocks_the_user_when_has_tried_to_login_more_than_5_times()
    {
        $user = User::factory()->create();
        $response = null;

        for ($i = 1; $i <= 6; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response->assertSessionHasErrors(['email']); //trans('auth.throttle', ['seconds' => 60])
        $this->assertGuest();
    }

    public function test_inactive_users_can_not_authenticate()
    {
        $days = 5;
        $user = User::factory()->banned($days)->create();

        $response = $this->followingRedirects()->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $message = trans('auth.account_suspended') . ' ' . trans('auth.suspended_days', ['days' => $days]) . '. ' . trans('auth.contact_administrator');
        $response->assertSee($message);
    }

    public function test_inactive_users_can_authenticate_after_ban_time(): void
    {
        $days = 5;
        $user = User::factory()->banned($days)->create();

        $this->travel($days + 1)->days();

        $response = $this->followingRedirects()->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $message = trans('auth.account_suspended') . ' ' . trans('auth.suspended_days', ['days' => $days]) . '. ' . trans('auth.contact_administrator');
        $response->assertDontSee($message);

        $response->assertSee(trans('app.dashboard'));
    }

    public function test_inactive_users_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
    }
}
