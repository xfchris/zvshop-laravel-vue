<?php

namespace App\Services\User;

use App\Events\BanUnbanUserEvent;
use App\Events\LogUserActionEvent;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateUser(Request $request, User $user): User
    {
        $user->fill($request->validated())->save();
        LogUserActionEvent::dispatch('user', 'User', $user->id, 'created');

        return $user;
    }

    public function setBanned(Request $request, User $user): Bool
    {
        $data = $request->validated();
        if (!$user->hasRole(config('permission.roles.admin.name'))) {
            $user->banned_until = ($data['banned_until']) ? now()->addDays($data['banned_until']) : null;

            if ($user->save()) {
                BanUnbanUserEvent::dispatch($user);
                LogUserActionEvent::dispatch('user', 'User', $user->id, $user->banned_until ? 'banned' : 'unbanned');

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

    public function loginApi(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return ['code' => 401, 'status' => 'FAILED', 'message' => 'Invalid user or password'];
        }
        return [
            'code' => 200,
            'access_token' => $user->createToken('authToken')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }
}
