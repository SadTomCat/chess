<?php

namespace App\Http\Controllers;

use App\Events\GameMoveEvent;
use App\Models\Game;
use App\Models\GameMove;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameMoveController extends Controller
{
    /**
     * @param Request $request
     * @param string $token
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $token): JsonResponse
    {
        try {
            $user = $request->user();
            $game = Game::where('token', $token)->firstOrFail();

            GameMove::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'type' => 'move',
                'from' => json_encode($request->move['from'], JSON_THROW_ON_ERROR),
                'to' => json_encode($request->move['to'], JSON_THROW_ON_ERROR),
            ]);

            broadcast(new GameMoveEvent($token, $request->move));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Game not exist'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }

        return response()->json(['status' => true]);
    }
}
