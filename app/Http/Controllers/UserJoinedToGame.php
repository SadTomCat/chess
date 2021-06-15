<?php

namespace App\Http\Controllers;

use App\Events\GameStartEvent;
use App\Game\GameTimings;
use App\Models\Game;
use App\Websockets\IWebsocketManager;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class UserJoinedToGame extends Controller
{
    /**
     * @param string $token
     * @param IWebsocketManager $manager
     * @return JsonResponse
     */
    public function __invoke(string $token, IWebsocketManager $manager): JsonResponse
    {
        try {
            $game = Game::getGameByToken($token);
            $moves = $game->moves()->get(['from', 'to', 'type', 'created_at']);
            $userCount = $manager->getChannelsInfo('presence-game-' . $token)['presence-game-' . $token]['user_count'];

            if ($userCount < 2 && $game->start_at === null) {
                return response()->json([
                    'status' => true,
                    'moves' => $moves,
                    'gameStarted' => false,
                ]);
            }

            if ($game->start_at !== null) {
                return $this->gameHasStarted($moves, $game);
            }

            $gameStartedCheckTime = $game->created_at->timestamp + GameTimings::GAME_START_WAITING_TIME;
            if (date('U') <= $gameStartedCheckTime - 1) {
                $this->startGame($token, $game);
            }

        } catch (Exception $e) {
            return response()->json(['status' => false]);
        }

        return response()->json([
            'status' => true,
            'moves' => $moves,
            'gameStarted' => true
        ]);
    }

    /**
     * @param string $token
     * @param Game $game
     */
    private function startGame(string $token, Game $game): void
    {
        $endAt = (int)date('U') + GameTimings::MOVE_TIME_WITH_DELAY;
        $game->update(['start_at' => date('Y-m-d H:i:s')]);
        event(new GameStartEvent($token, $endAt));
    }

    /**
     * @param Collection $moves
     * @param Game $game
     * @return JsonResponse
     */
    private function gameHasStarted(Collection $moves, Game $game): JsonResponse
    {
        $gameEndAt = count($moves) > 0
            ? (int)$moves->last()->created_at->timestamp + GameTimings::MOVE_TIME_WITH_DELAY
            : (int)Carbon::createFromDate($game->start_at)->timestamp + GameTimings::MOVE_TIME_WITH_DELAY;

        return response()->json([
            'status' => true,
            'moves' => $moves,
            'gameStarted' => true,
            'endAt' => $gameEndAt
        ]);
    }
}
