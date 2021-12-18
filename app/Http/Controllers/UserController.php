<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(
        public UserService $userService
    ) {
    }

    public function index(): View
    {
        $users = User::with('roles:id,name')->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'banned_until'])
        ->paginate(config('constants.num_rows_per_table'));
        return view('users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->userService->updateUser($request->all(), $user);
        return redirect()->route('admin.users.index')->with('success', 'User update!');
    }
}
