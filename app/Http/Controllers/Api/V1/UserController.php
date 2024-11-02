<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\UserFilterRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(UserFilterRequest $request): JsonResponse
    {
        $filters = $request->only(['provider', 'status', 'balanceMin', 'balanceMax', 'currency']);
        $perPage = $request->input('per_page', 100);
        $page = $request->input('page', 1);

        $users = $this->userService
            ->getUsers($filters)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->all();

        return response()->json([
            'data' => $users,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage
            ]
        ]);
    }
}
