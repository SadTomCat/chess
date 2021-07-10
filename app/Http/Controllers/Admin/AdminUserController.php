<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCreateUserRequest;
use App\Http\Requests\Admin\AdminUpdateRoleRequest;
use App\Models\User;
use Faker\Factory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $requestColumns = $request->query('columns', []);

        $columns = is_array($requestColumns) === true && empty($requestColumns) === false
            ? $requestColumns
            : $user->getVisible();

        $userInfo = $user->getUserInfo($columns, $request->boolean('need_statistics', false));

        return response()->json($userInfo);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function block(User $user): JsonResponse
    {
        $status = $user->update([
            'blocked'    => true,
            'blocked_at' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'     => $status,
            'blocked_at' => $user->blocked_at,
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function unblock(User $user): JsonResponse
    {
        $status = $user->update([
            'blocked'    => false,
            'blocked_at' => null,
        ]);

        return response()->json(['status' => $status]);
    }

    /**
     * @param AdminUpdateRoleRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updateRole(AdminUpdateRoleRequest $request, User $user): JsonResponse
    {
        $this->authorize('updateRole', [$user, $request->role]);

        $status = $user->update([
            'role' => $request->role,
        ]);

        return response()->json(['status' => $status]);
    }

    /**
     * Authorize in middleware
     *
     * @param AdminCreateUserRequest $request
     * @return JsonResponse
     */
    public function createUser(AdminCreateUserRequest $request): JsonResponse
    {
        User::create([
            'name'              => $request->name ?? Factory::create()->firstName(),
            'email'             => $request->email,
            'role'              => $request->role,
            'password'          => Hash::make($request->password),
            'email_verified_at' => Date::now(),
        ]);

        return response()->json(['status' => true]);
    }
}
