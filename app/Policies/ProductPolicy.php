<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users_show_products');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('users_show_products');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users_create_products');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('users_update_products');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('users_disable_products');
    }
}
