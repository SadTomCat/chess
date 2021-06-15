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
    public function show(Request $request, User $user): JsonResponse
    {
        try {
            $aboutUser = $user->only(['id', 'name', 'email', 'blocked', 'created_at', 'updated_at']);

            if ($aboutUser['blocked'] === true) {
                $aboutUser['blocked_at'] = $user->blocked_at;
            }

            $info = array_merge($aboutUser, [
                'count_games' => $user->games()->count(),
                'count_won' => $user->countGamesWon(),
                'not_count_games' => $user->games()->where('end_at', null)->count()
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }

        return response()->json($info);
    }

    public function block(Request $request, User $user): JsonResponse
    {
        $status = $user->update([
            'blocked' => true,
            'blocked_at' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status' => $status,
            'blocked_at' => $user->blocked_at,
        ]);
    }

    public function unblock(Request $request, User $user): JsonResponse
    {
        $status = $user->update([
            'blocked' => false,
            'blocked_at' => null,
        ]);

        return response()->json(['status' => $status]);
    }
}
