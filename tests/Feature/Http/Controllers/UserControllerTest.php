<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_redirect_to_login(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_it_can_not_redirect_to_login_in_json_requests(): void
    {
        $response = $this->getJson(route('users.index'));

        $response->assertStatus(401);
    }

    public function test_it_show_the_user_management(): void
    {
        $user = $this->userAdminCreate();
        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.user_management.title'));
    }

    public function test_it_show_the_edit_form_with_admin_account(): void
    {
        $user = $this->userAdminCreate();
        $response = $this->actingAs($user)->get(route('users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertSee(trans('app.user_management.edit_user'));
    }

    public function test_it_show_the_edit_form_with_user_account(): void
    {
        $user = $this->userClientCreate();
        $response = $this->actingAs($user)->get(route('users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertSee(trans('app.user_management.edit_user'));
    }

    public function test_it_not_show_edit_form_to_a_user_who_is_not_the_owner(): void
    {
        $otherUser = $this->userClientCreate();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.edit', $otherUser->id));

        $response->assertStatus(403);
    }

    public function test_it_can_update_a_user(): void
    {
        $user = $this->userAdminCreate();
        $data = [
                'name' => 'New Name',
                'document_type' => 'CC',
                'document' => '123',
            ];
        $response = $this->actingAs($user)->put(route('users.update', $user->id), $data);

        $userCheck = User::find($user->id);
        $this->assertSame($userCheck->name, $data['name']);
        $response->assertRedirect(route('users.edit', $user->id));
    }

    /**
     * @dataProvider usersDataProvider
     * @param string $field
     * @param mixed|null $value
     */
    public function test_it_show_errors_when_data_is_incorrect_in_update_user(string $field, $value = null): void
    {
        $user = $this->userAdminCreate();
        $data = [];
        $data[$field] = $value;
        $response = $this->actingAs($user)->put(route('users.update', $user->id), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors($field);
    }

    public function usersDataProvider(): array
    {
        return [
            'Test the name is required' => ['name', null],
            'Test the name is too long' => ['name', Str::random(81)],
        ];
    }
}
