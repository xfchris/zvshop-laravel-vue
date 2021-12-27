<?php

namespace App\Services\User;

use App\Events\BanUnbanUserEvent;
use App\Models\User;
use App\Services\Trait\NotifyLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserService
{
    use NotifyLog;

    public function updateUser(Request $request, User $user): User
    {
        $user->fill($request->validated())->save();
        $this->notifyLog('User', $user->id, 'created');

        return $user;
    }

    public function setBanned(Request $request, User $user): Bool
    {
        $data = $request->validated();
        if (!$user->hasRole(config('permission.roles.admin.name'))) {
            $user->banned_until = ($data['banned_until']) ? now()->addDays($data['banned_until']) : null;

            if ($user->save()) {
                BanUnbanUserEvent::dispatch($user);
                $this->notifyLog('User', $user->id, $user->banned_until ? 'banned' : 'unbanned');

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
