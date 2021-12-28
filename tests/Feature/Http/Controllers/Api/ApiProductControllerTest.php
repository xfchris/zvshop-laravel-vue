<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\ContextImageFake;

class ApiProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use ContextImageFake;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeInstanceImage();
    }

    public function test_it_can_delete_a_image(): void
    {
        $userAdmin = $this->userAdminCreate();
        $product = Product::factory()->create();
        $product->images()->createMany([
            ['url' => 'https://i.imgur.com/fakehash1.jpg', 'data' => ['deletehash' => 'fakeDeleteHash1']],
        ]);
        $image = $product->images->first();
        $response = $this->actingAs($userAdmin)->delete(route('api.images.destroy', $image->id));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertSame(0, $product->fresh()->images->count());
    }

    ////////
    public function test_it_can_inactivate_a_user(): void
    {
        $userAdmin = User::find(1);
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

        $userAdmin = User::find(1);
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $user->id), ['banned_until' => null]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertNull(User::find($user->id)->check_banned_until);
    }

    public function test_it_show_errors_when_is_invalid_the_post_data(): void
    {
        Notification::fake();
        $response = $this->executeAdminTest(['banned_untilxx' => 5], 'error');
        Notification::assertNotSentTo(User::find(1), SendBanUnbanNotification::class);
        $response->assertStatus(422);
    }

    private function executeAdminTest(array $postData, string $assertStatus): TestResponse
    {
        $userAdmin = User::find(1);
        $response = $this->actingAs($userAdmin)->post(route('api.users.activateInactivateUser', $userAdmin->id), $postData);

        $response->assertJson(['status' => $assertStatus]);
        return $response;
    }
}
