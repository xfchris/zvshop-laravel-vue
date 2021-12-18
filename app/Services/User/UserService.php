<?php

namespace App\Services\User;

use App\Models\User;

class UserService
{
    public function updateUser(array $data, User $user): User
    {
        $user->fill($data)->save();
        return $user;
    }

    public function setBanned(array $data, User $user): Bool
    {
        if (!$user->hasRole(config('permission.roles.admin.name'))) {
            $user->banned_until = ($data['banned_until']) ? now()->addDays($data['banned_until']) : null;
            return (bool)$user->save();
        }
        return false;
    }
}
