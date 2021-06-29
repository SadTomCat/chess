<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\GameInfo;
use App\Models\Game;
use App\Models\User;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminGamesController extends Controller
{
    use GameInfo;

    /**
     * Validation in middleware
     *
     * Successful {
     *      game: {
     *          end_at:       String
     *          id:           int
     *          start_at:     String
     *          token:        String
     *          winner_color: String
     *      },
     *
     *     moves: [
     *          {
     *              from: {x: int, y: int}
     *              to:   {}
     *          }
     *      ],
     *
     *      users: {
     *          black: {
     *              id:          int
     *              name:        String
     *              email        String
     *              role         String
     *              blocked:     bool
     *              count_games: int
     *              count_won:   int
     *          }
     *          white: {}
     *      }
     * }
     *
     * @param Request $request
     * @param Game $game
     *
     * @return JsonResponse
     */
    public function showGameInfo(Request $request, Game $game): JsonResponse
    {
        $info = array_merge(
            ['status' => true],
            $this->getGameInfo($game),
        );

        return response()->json($info);
    }
}
