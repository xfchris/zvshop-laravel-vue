<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginApiRequest;
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

    public function login(LoginApiRequest $request): JsonResponse
    {
        $response = $this->userService->loginApi($request->email, $request->password);
        return response()->json($response, 200);
    }

    public function setbanned(UserActiveInactiveRequest $request, User $user): JsonResponse
    {
        $this->authorize('can', 'users_update_users');

        $banned = $this->userService->setBanned($request, $user);
        $response = $banned ? ['status' => 200, 'message' => 'User banned']
                            : ['status' => 400, 'message' => 'The account role is admin'];

        return response()->json($response, $response['status']);
    }
}
