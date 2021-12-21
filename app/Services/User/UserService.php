<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function getUsersPerPage(): LengthAwarePaginator
    {
        return User::with('roles:id,name')
                ->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'banned_until'])
                ->orderBy('created_at', 'DESC')
                ->paginate(config('constants.num_rows_per_table'));
    }
}
