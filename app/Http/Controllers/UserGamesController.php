<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginateGamesForUserRequest;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserGamesController extends Controller
{
    /**
     * @param PaginateGamesForUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function paginate(PaginateGamesForUserRequest $request, User $user): JsonResponse
    {
        return response()->json(Game::paginateForUser($user->id, $request->page));
    }
}
