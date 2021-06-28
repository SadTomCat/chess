<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $currentUser
     * @param User $gamesOwner
     *
     * @return bool
     */
    public function seeGames(User $currentUser, User $gamesOwner): bool
    {
        $isRoleAuthorized = in_array($currentUser->role, ['admin', 'support', 'moderator'], true);

        return $isRoleAuthorized || ($currentUser->id === $gamesOwner->id);
    }
}
