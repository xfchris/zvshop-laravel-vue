<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ApiUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_inactivate_a_user(): void
    {
        $userAdmin = User::find(1);
        $user = $this->userClientCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $user->id), ['banned_until' => 5]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertSame(now()->addDays(5)->format('d/m/Y'), User::find($user->id)->banned_until->format('d/m/Y'));
    }

    public function test_it_can_not_inactive_a_user_with_admin_role(): void
    {
        $response = $this->executeAdminTest(['banned_until' => 5]);
        $response->assertStatus(200);
    }

    public function test_it_show_errors_when_is_invalid_the_post_data(): void
    {
        $response = $this->executeAdminTest(['banned_untilxx' => 5]);
        $response->assertStatus(422);
    }

    private function executeAdminTest(array $postData): TestResponse
    {
        $userAdmin = User::find(1);
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $userAdmin->id), $postData);

        $response->assertJson(['status' => 'error']);
        return $response;
    }
}
