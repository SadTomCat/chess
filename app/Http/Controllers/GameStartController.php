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

            $moveNum = Game::where('token', $token)->first()->moves()->count();
            event(new GameStartEvent($token, $moveNum));

        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }

        return response()->json(['status' => true]);
    }
}
