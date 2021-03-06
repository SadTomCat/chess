<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pagination\PaginateGamesForUserRequest;
use App\Models\Game;
use App\Models\User;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getRole(Request $request): JsonResponse
    {
        return response()->json(['role' => $request->user()->role]);
    }

    /**
     * @param PaginateGamesForUserRequest $request
     * @param User $user
     *
     * @return JsonResponse
     */
    public function paginateGames(PaginateGamesForUserRequest $request, User $user): JsonResponse
    {
        return response()->json(Game::paginateForUser($user->id, $request->page));
    }

    /**
     * Successful [
     *      count_games: int
     *      count_won: int
     *      not_count_games: int
     * ]
     *
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function getGamesStatistics(User $user): JsonResponse
    {
        Gate::authorize('isOwner', [User::class, $user]);

        return response()->json(['statistics' => $user->getGamesStatistics()]);
    }
}
