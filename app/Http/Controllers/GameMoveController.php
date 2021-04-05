<?php

namespace App\Http\Controllers;

use App\Events\GameMoveEvent;
use App\Game\MoveValidation;
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
            $game = $user->getGameByToken($token);
            $moveValidator = new MoveValidation($user->id, $game, ...$request->move);
            $moveInfo = $moveValidator->validate();

            if ($moveInfo->getStatus() === false) {
                return response()->json($moveInfo->getArrayFailed());
            }

            GameMove::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'type' => 'peace',
                'from' => $request->move['from'],
                'to' => $request->move['to'],
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

        return response()->json(['status' => true, 'message' => 'What went wrong']);
    }
}
