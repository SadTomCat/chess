<?php

namespace App\Http\Controllers;

use App\Events\GameNewMessageEvent;
use App\Http\Requests\GameChatRequest;
use App\Models\Game;
use App\Models\GameMessage;
use Illuminate\Http\JsonResponse;

class GameChatController extends Controller
{
    /**
     * This route send message in chat
     *
     * Auth and BelongsGame middlewares were called before this route.
     *
     * @param GameChatRequest $request
     * @param string $token
     * @return JsonResponse
     */
    public function newMessage(GameChatRequest $request, string $token): JsonResponse
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

        return response()->json(['status' => true]);
    }
}
