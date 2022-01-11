<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function update(User $userAuth, Order $order): bool
    {
        if ($userAuth->hasPermissionTo('user_manage_own_order')) {
            return $userAuth->id === $order->user->id;
        }
        return false;
    }
}
