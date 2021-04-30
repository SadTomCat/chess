<?php

namespace App\Http\Controllers;

use App\Events\GameStartEvent;
use App\Game\GameTimings;
use App\Models\Game;
use App\Websockets\IWebsocketManager;
use Exception;
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

            $userCount = $manager
                ->getChannelsInfo('presence-game-' . $token)['presence-game-' . $token]['user_count'];

            if ($userCount !== 2 || $game->start_at !== null) {
                $endAt = count($moves) > 0
                    ? (int)$moves->last()->created_at->timestamp + GameTimings::MOVE_TIME_WITH_DELAY
                    : (int)Carbon::createFromDate($game->start_at)->timestamp + (GameTimings::MOVE_TIME_WITH_DELAY ?? 0);

                return response()->json([
                    'status' => true,
                    'moves' => $moves,
                    'gameStarted' => $game->start_at !== null,
                    'endAt' => $endAt
                ]);
            }

            $gameStartedCheckTime = $game->created_at->timestamp + GameTimings::GAME_STARTED_CHECK;

            if (date('U') <= $gameStartedCheckTime - 1) {
                $endAt = (int)date('U') + GameTimings::MOVE_TIME_WITH_DELAY;
                $game->update(['start_at' => date('Y-m-d H:i:s')]);
                event(new GameStartEvent($token, $endAt));
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
}
