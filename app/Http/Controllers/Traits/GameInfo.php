<?php

namespace App\Http\Controllers\Traits;

use App\Models\Game;
use App\Models\User;
use App\Services\GameService;

trait GameInfo
{
    /**
     * @param Game $game
     *
     * @return array ['game' => array, 'moves'  => array, 'users' => array]
     */
    public function getGameInfo(Game $game): array
    {
        $gameService = new GameService($game);
        $gameInfo = $gameService->getGameInfo();

        return array_merge(
            ['users' => $this->getUsersInfo($game)],
            $gameInfo
        );
    }

    /**
     * @param Game $game
     * @return array
     */
    protected function getUsersInfo(Game $game): array
    {
        $columns = $this->getColumnsForUsersInfo();
        $users = $game->users()->withPivot(['color'])->get();

        /** @var User $user */
        foreach ($users as $user) {
            $info[$user->pivot->color] = array_merge(
                $user->getUserInfo($columns),
                [
                    'count_games' => $user->countEndedGames(),
                    'count_won'   => $user->countGamesWon()
                ]
            );
        }

        return $info ?? [];
    }

    /**
     * @return string[]
     */
    protected function getColumnsForUsersInfo(): array
    {
        return ['id', 'name', 'email', 'role', 'blocked'];
    }
}
