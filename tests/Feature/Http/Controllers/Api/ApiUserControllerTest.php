<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_show_the_user_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('api.users'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['recordsTotal' => 1]);
        $response->assertSee($user->name);
    }
}
