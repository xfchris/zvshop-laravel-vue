<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use App\Notifications\SendBanUnbanNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ApiUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_inactivate_a_user(): void
    {
        $userAdmin = $this->userAdminCreate();
        $user = $this->userClientCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $user->id), ['banned_until' => 5]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertSame(now()->addDays(5)->format('d/m/Y'), User::find($user->id)->banned_until->format('d/m/Y'));
    }

    public function test_it_can_not_inactive_an_user_with_admin_role(): void
    {
        $response = $this->executeAdminTest(['banned_until' => 5], 'error');
        $response->assertStatus(200);
    }

    public function test_it_can_activate_an_user(): void
    {
        $user = $this->userClientCreate();
        $user->banned_until = now()->addDays(5);
        $user->save();

        $userAdmin = $this->userAdminCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $user->id), ['banned_until' => null]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertNull(User::find($user->id)->check_banned_until);
    }

    public function test_it_show_errors_when_is_invalid_the_post_data(): void
    {
        Notification::fake();
        $userAdmin = $this->userAdminCreate();
        $response = $this->executeAdminTest(['banned_untilxx' => 5], 'error');
        Notification::assertNotSentTo($userAdmin, SendBanUnbanNotification::class);
        $response->assertStatus(422);
    }

    private function executeAdminTest(array $postData, string $assertStatus): TestResponse
    {
        $userAdmin = $this->userAdminCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $userAdmin->id), $postData);

        $response->assertJson(['status' => $assertStatus]);
        return $response;
    }
}
