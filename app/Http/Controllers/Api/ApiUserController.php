<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserActiveInactiveRequest;
use App\Models\User;
use App\Services\User\UserService;

class ApiUserController extends Controller
{
    public function __construct(
        public UserService $userService
    ) {
    }

    public function activateInactivateUser(UserActiveInactiveRequest $request, User $user)
    {
        $status = 'error';
        $message = 'The account role is admin';

        if ($this->userService->setBanned($request->all(), $user)) {
            $status = 'success';
            $message = '';
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
