<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Traits\AdminUserAuthorization;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AdminUserAuthorization;

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

    /**
     * @param User $currentUser
     * @param User $needleUser
     *
     * @return bool
     */
    public function isOwner(User $currentUser, User $needleUser): bool
    {
        return $currentUser->id === $needleUser->id;
    }
}
