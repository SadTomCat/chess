<?php

namespace App\Policies;

use App\Models\Rule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RulePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function anyAction(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'redactor';
    }
}
