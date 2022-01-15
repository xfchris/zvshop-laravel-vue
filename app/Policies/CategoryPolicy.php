<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
}
