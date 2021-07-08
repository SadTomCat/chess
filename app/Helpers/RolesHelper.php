<?php

namespace App\Helpers;

use JetBrains\PhpStorm\Pure;

class RolesHelper
{
    private array $roles;

    private array $mappedAvailableRolesForUpdate;

    public function __construct()
    {
        $this->roles = config('enums.user_roles');
        $this->mappedAvailableRolesForUpdate = config('admin.mapped_available_roles_for_update');

        if (isset($this->mappedAvailableRolesForUpdate['admin']) === false) {
            $this->mappedAvailableRolesForUpdate['admin'] = $this->getAvailableRolesForAdmin();
        }

        foreach ($this->mappedAvailableRolesForUpdate as $personRole => $availableRoles) {
            if (in_array('user', $availableRoles, true) === false) {
                $this->mappedAvailableRolesForUpdate[$personRole][] = 'user';
            }
        }
    }

    /**
     * @param string $role
     * @return array
     */
    public function getAvailableRolesByRole(string $role): array
    {
        return [...$this->mappedAvailableRolesForUpdate[$role]] ?? [];
    }

    /**
     * @return array
     */
    public function getAuthorizedEditors(): array
    {
        return array_keys($this->mappedAvailableRolesForUpdate);
    }

    /**
     * @return array
     */
    #[Pure] public function getUnauthorizedEditors(): array
    {
        return array_diff($this->roles, $this->getAuthorizedEditors());
    }

    /**
     * @param string $separator
     * @return string
     */
    #[Pure] public function getImplodedAuthorizedEditors(string $separator): string
    {
        return implode($separator, $this->getAuthorizedEditors());
    }

    /**
     * @return array
     */
    private function getAvailableRolesForAdmin(): array
    {
        return array_filter($this->roles, fn($value) => $value !== 'admin');
    }
}
