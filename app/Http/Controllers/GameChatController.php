<?php

namespace App\Http\Controllers;

use App\Events\GameNewMessageEvent;
use App\Http\Requests\GameChatRequest;
use App\Models\Game;
use App\Models\GameMessage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class GameChatController extends Controller
{
    /**
     * @param GameChatRequest $request
     * @param string $token
     * @return Response|Application|ResponseFactory
     */
    public function newMessage(GameChatRequest $request, string $token): Response|Application|ResponseFactory
    {
        $message = $request->message;
        $user = $request->user();

        $game = Game::where('token', $token)->latest()->first();

        GameMessage::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'message' => $message,
        ]);

        broadcast(new GameNewMessageEvent($user, $token, $message))->toOthers();

        return response(['status' => true]);
    }
}
