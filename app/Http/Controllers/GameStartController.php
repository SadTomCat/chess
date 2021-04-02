<?php

namespace App\Http\Controllers;

use App\Events\GameStartEvent;
use App\Models\Game;
use App\Websockets\IWebsocketManager;

class GameStartController extends Controller
{
    public function __invoke(string $token, IWebsocketManager $manager)
    {
        try {
            $userCount = $manager
                ->getChannelsInfo('presence-game-' . $token)['presence-game-' . $token]['user_count'];

            if ($userCount !== 2) {
                return response()->json(['status' => true]);
            }

            $moves = Game::where('token', $token)->firstOrFail()->moves()->get(['from', 'to', 'type'])->toArray();

            event(new GameStartEvent($token, $moves));

        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }

        return response()->json(['status' => true]);
    }
}
