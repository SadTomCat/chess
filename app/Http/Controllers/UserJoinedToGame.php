<?php

namespace App\Http\Controllers;

use App\Events\GameStartEvent;
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
            $moves = $game->moves()->get(['from', 'to', 'type'])->toArray();

            $userCount = $manager
                ->getChannelsInfo('presence-game-' . $token)['presence-game-' . $token]['user_count'];

            if ($userCount !== 2 || $game->start_at !== null) {
                $endAt = count($moves) > 0
                    ? (int)$game->moves()->latest()->first('created_at')->created_at->timestamp + 122
                    : (int)Carbon::createFromDate($game->start_at)->timestamp + 122 ?? 0;

                return response()->json([
                    'status' => true,
                    'moves' => $moves,
                    'gameStarted' => $game->start_at !== null,
                    'endAt' => $endAt
                ]);
            }

            $game->update(['start_at' => date('Y-m-d H:i:s')]);
            event(new GameStartEvent($token, (int)date('U') + 122));

        } catch (Exception $e) {
            return response()->json(['status' => false]);
        }

        return response()->json(['status' => true, 'moves' => $moves, 'gameStarted' => true]);
    }
}
