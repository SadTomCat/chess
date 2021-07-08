<?php

namespace App\Policies\Traits;

use App\Helpers\RolesHelper;
use App\Models\User;

trait AdminUserAuthorization
{
    /**
     * @param User $currentUser
     * @param User $userForUpdate
     * @param string $role - should be valid role name
     * @return bool
     */
    public function updateRole(User $currentUser, User $userForUpdate, string $role): bool
    {
        $this->assertNotSame($currentUser->id, $userForUpdate->id);
        $this->assertNotSame($currentUser->role, $role);
        $this->assertNotSame($currentUser->role, $userForUpdate->role);
        $this->assertNotSame($role, 'admin');

        if ($currentUser->role === 'admin') {
            return true;
        }

        $mappedAvailableRolesForUpdate = app(RolesHelper::class)->getAvailableRolesByRole($currentUser->role);

        return in_array($userForUpdate->role, $mappedAvailableRolesForUpdate, true)
            && in_array($role, $mappedAvailableRolesForUpdate, true);
    }

    /**
     * @param $val1
     * @param $val2
     */
    private function assertNotSame($val1, $val2): void
    {
        if ($val1 === $val2) {
            abort(403, 'Unauthorized action');
        }
    }
}
