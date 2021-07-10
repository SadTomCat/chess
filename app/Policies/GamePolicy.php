<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Game $game
     *
     * @return bool
     */
    public function memberOfGame(User $user, Game $game): bool
    {
        return $game->users->find(['id' => $user->id])?->isNotEmpty() ?? false;
    }
}
