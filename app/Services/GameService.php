<?php

namespace App\Services;

use App\Events\GameEndEvent;
use App\Events\GameMoveEvent;
use App\Game\MoveInfo;
use App\Models\Game;
use App\Models\GameMove;
use App\Models\User;

class GameService
{
    /**
     * @param Game $game
     * @param User $user
     * @param MoveInfo $moveInfo
     *
     * @throws \Exception
     */
    public static function successfulMove(Game $game, User $user, MoveInfo $moveInfo): void
    {
        $move = $moveInfo->getMoveArray();
        $token = $game->token;

        GameMove::create(array_merge(['user_id' => $user->id, 'game_id' => $game->id,], $move));

        if ($moveInfo->getType() === 'mate') {
            broadcast(new GameEndEvent($game->token, $game->getUserColor($user->id), $move));
            return;
        }

        broadcast(new GameMoveEvent($token, $move));
    }
}
