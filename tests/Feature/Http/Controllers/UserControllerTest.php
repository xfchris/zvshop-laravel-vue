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
        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_it_show_the_user_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('User Management');
    }

    public function test_it_show_the_edit_form(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertSee('User update');
    }

    public function test_it_can_update_a_user(): void
    {
        $user = User::factory()->create();
        $data = ['name' => 'New Name'];

        $response = $this->actingAs($user)->put(route('admin.users.update', $user->id), $data);

        $userCheck = User::find($user->id);
        $this->assertSame($userCheck->name, $data['name']);
        $response->assertRedirect(route('admin.users.index'));
    }

    /**
     * @dataProvider usersDataProvider
     * @param string $field
     * @param mixed|null $value
     */
    public function test_it_show_errors_when_data_is_incorrect_in_update_user(string $field, $value = null): void
    {
        $user = User::factory()->create();
        $data = [];
        $data[$field] = $value;
        $response = $this->actingAs($user)->put(route('admin.users.update', $user->id), $data);

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
