<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $aboutUser = $user->only(['id', 'name', 'email', 'blocked', 'created_at', 'updated_at']);

        if ($aboutUser['blocked'] === true) {
            $aboutUser['blocked_at'] = $user->blocked_at;
        }

        $info = array_merge($aboutUser, $user->getGamesStatistics());

        return response()->json($info);
    }

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

    public function unblock(User $user): JsonResponse
    {
        $status = $user->update([
                                    'blocked'    => false,
                                    'blocked_at' => null,
                                ]);

        return response()->json(['status' => $status]);
    }
}
