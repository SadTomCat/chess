<?php

namespace App\Http\Controllers;

use App\Events\GameMoveEvent;
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
        broadcast(new GameMoveEvent($token, $request->move));
        return response()->json(['status' => true]);
    }
}
