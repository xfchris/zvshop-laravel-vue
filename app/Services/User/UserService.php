<?php

namespace App\Services\User;

use App\Events\BanUnbanUserEvent;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserService
{
    public function updateUser(Request $request, User $user): User
    {
        $user->fill($request->validated())->save();
        return $user;
    }

    public function setBanned(Request $request, User $user): Bool
    {
        $data = $request->validated();
        if (!$user->hasRole(config('permission.roles.admin.name'))) {
            $user->banned_until = ($data['banned_until']) ? now()->addDays($data['banned_until']) : null;

            if ($user->save()) {
                BanUnbanUserEvent::dispatch($user);
                return true;
            }
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
