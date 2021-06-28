<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RuleCategoryPolicy
{
    use HandlesAuthorization;

    public function anyAction(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'redactor';
    }
}
