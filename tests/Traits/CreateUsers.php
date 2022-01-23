<?php

namespace Tests\Traits;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait CreateUsers
{
    protected function userClientCreate(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole(config('permission.roles.clients.name'));
        return $user;
    }

    public function userAdminCreate(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole(config('permission.roles.admin.name'));
        return $user;
    }

    public function userAdminApiCreate(array $attributes = []): User
    {
        $user = $this->userAdminCreate($attributes);
        Sanctum::actingAs($user);
        return $user;
    }
}
