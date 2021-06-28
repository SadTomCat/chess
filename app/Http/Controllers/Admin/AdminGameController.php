<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

        /** @var User $user */
        foreach ($users as $user) {
            $info[$user->pivot->color] = array_merge($user->getUserInfo(),
                ['count_games' => $user->countEndedGames(), 'count_won' => $user->countGamesWon()]);
        }

        return $info ?? [];
    }
}
