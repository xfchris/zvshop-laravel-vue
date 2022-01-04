<?php

namespace Tests\Traits;

use App\Models\User;

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
}
