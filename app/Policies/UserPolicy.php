<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $userAuth, User $user): bool
    {
        if ($userAuth->hasPermissionTo('users_update_users')) {
            return true;
        } elseif ($userAuth->hasPermissionTo('users_update_own_users')) {
            return $userAuth->id === $user->id;
        }
        return false;
    }
}
