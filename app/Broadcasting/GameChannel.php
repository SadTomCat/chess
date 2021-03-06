<?php

namespace App\Broadcasting;

use App\Models\Game;
use App\Models\User;
use App\Services\GameService;

class GameChannel
{
    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @param string $token
     * @return array|bool
     */
    public function join(User $user, string $token): bool|array
    {
        $notInOtherGame = GameService::notInGame($user, $token);

        if (!$notInOtherGame) {
            return false;
        }

        $game = Game::getGameByToken($token);

        $exists = $game->users->find($user->id);

        if ($exists === null) {
            return false;
        }

        $color = $user->games()->firstWhere('token', $token)->pivot->color;

        return ['id' => $user->id, 'name' => $user->name, 'color' => $color];
    }
}
