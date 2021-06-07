<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminGameController extends Controller
{
    public function show(Request $request, Game $game): JsonResponse
    {
        try {
            $moves = $game->moves()->get(['from', 'to', 'type'])->toArray();
            $gameInfo = $game->only(['id', 'token', 'start_at', 'end_at', 'winner_color']);
            $info = [
                'status' => true,
                'game' => $gameInfo,
                'users' => $this->getUsersInfo($game),
                'moves' => $moves,
            ];

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }

        return response()->json($info);
    }

    /**
     * @param Game $game
     * @return array
     */
    private function getUsersInfo(Game $game): array
    {
        $users = $game->users()->withPivot(['color'])->get();

        foreach ($users as $user) {
            $info[$user->pivot->color] = array_merge($user->only(['id', 'name', 'email']),
                ['count_games' => $user->countEndedGames(), 'count_won' => $user->countGamesWon()]);
        }

        return $info ?? [];
    }
}
