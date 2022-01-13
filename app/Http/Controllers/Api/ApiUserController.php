<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserActiveInactiveRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class ApiUserController extends Controller
{
    public function __construct(
        public UserService $userService
    ) {
    }

    public function activateInactivateUser(UserActiveInactiveRequest $request, User $user): JsonResponse
    {
        if ($this->userService->setBanned($request, $user)) {
            $status = 'success';
            $message = '';
        } else {
            $status = 'error';
            $message = 'The account role is admin';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
