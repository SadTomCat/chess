<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\GameInfo;
use App\Models\Game;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    use GameInfo;

    /**
     * Successful {
     *      game: {
     *          end_at:       String
     *          id:           int
     *          start_at:     String
     *          token:        String
     *          winner_color: String
     *      },
     *      moves: [
     *          {
     *              from: {x: int, y: int}
     *              to:   {}
     *          }
     *      ],
     *      users: {
     *          black: {
     *              blocked:     bool
     *              count_games: int
     *              count_won:   int
     *              name:        String
     *          }
     *          white: {}
     *      }
     * }
     *
     * @param Request $request
     *
     * @param Game $game
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function showGameInfo(Request $request, Game $game): JsonResponse
    {
        Gate::authorize('memberOfGame', [Game::class, $game]);

        return response()->json($this->getGameInfo($game));
    }

    /**
     * @return array
     */
    protected function getColumnsForUsersInfo(): array
    {
        return ['name', 'blocked'];
    }
}
