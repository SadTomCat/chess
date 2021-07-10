<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\ApiTestCase;

class AdminUpdateUserRoleTest extends ApiTestCase
{
    private array $roles;

    /**
     * Key - how can set
     * Value is roles that can be set and accesses roles of the user for update.
     *
     * @var array
     */
    private array $mappedAvailableRolesForUpdate;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->roles = config('enums.user_roles');
        $this->assertNotEmpty($this->roles);

        $this->mappedAvailableRolesForUpdate = config('admin.mapped_available_roles_for_update');
        $this->assertNotEmpty($this->mappedAvailableRolesForUpdate);

        if (isset($this->mappedAvailableRolesForUpdate['admin']) === false) {
            $this->mappedAvailableRolesForUpdate['admin'] = $this->getAvailableRolesForAdmin();
        }

        foreach ($this->mappedAvailableRolesForUpdate as $personRole => $availableRoles) {
            if (in_array('user', $availableRoles, true) === false) {
                $this->mappedAvailableRolesForUpdate[$personRole][] = 'user';
            }
        }
    }

    public function test_unauthenticated(): void
    {
        $payload = $this->getRequestPayload('user');

        $response = $this->patch($this->getEndpoint(), $payload);

        $response->assertStatus(401);
    }

    public function test_status_unauthorized_person(): void
    {
        $payload = $this->getRequestPayload('user');

        $endpoint = $this->getEndpoint();
        $unauthorizedPersonRoles = array_diff($this->roles, array_keys($this->mappedAvailableRolesForUpdate));

        foreach ($unauthorizedPersonRoles as $unauthorizedPersonRole) {
            $user = User::factory()->getUser('password', $unauthorizedPersonRole);
            $response = $this->actingAs($user)->patch($endpoint, $payload);

            $response->assertStatus(403);
        }
    }

    public function test_create_person_set_not_exists_role(): void
    {
        $personsCanSetAnyRole = array_keys($this->mappedAvailableRolesForUpdate);
        $payloadWithTrashRole = $this->getRequestPayload('trash');

        $endpoint = $this->getEndpoint();

        foreach ($personsCanSetAnyRole as $personRole) {
            $person = User::factory()->getUser('password', $personRole);

            $response = $this->actingAs($person)->patch($endpoint, $payloadWithTrashRole);
            $response->assertStatus(422);
        }
    }

    /**
     * All authorized persons try to set unavailable roles for users of all roles types
     */
    public function test_status_set_unavailable_roles(): void
    {
        foreach ($this->roles as $userRole) {
            $endpoint = $this->getEndpoint($userRole);
            $mappedUnavailableRoles = $this->mapUnavailableRoles();

            foreach ($mappedUnavailableRoles as $personRole => $unavailableRoles) {
                $person = User::factory()->getUser('password', $personRole);

                foreach ($unavailableRoles as $unavailableRole) {
                    $payload = $this->getRequestPayload($unavailableRole);

                    $response = $this->actingAs($person)->patch($endpoint, $payload);

                    $response->assertStatus(403);
                }
            }
        }
    }

    /**
     * All authorized persons try to set all roles types for unavailable users
     */
    public function test_person_set_for_unavailable_users(): void
    {
        $mappedUnavailableUserRoles = $this->mapUnavailableRoles();

        foreach ($mappedUnavailableUserRoles as $personRole => $unavailableUserRoles) {
            $person = User::factory()->getUser('password', $personRole);

            foreach ($unavailableUserRoles as $unavailableUserRole) {
                $endpoint = $this->getEndpoint($unavailableUserRole);

                foreach ($this->roles as $roleForSet) {
                    $payload = $this->getRequestPayload($roleForSet);

                    $response = $this->actingAs($person)->patch($endpoint, $payload);

                    $response->assertStatus(403);
                }
            }
        }
    }

    public function test_successful_role_update(): void
    {
        foreach ($this->mappedAvailableRolesForUpdate as $personRole => $availableRoles) {
            $person = User::factory()->getUser(role: $personRole);

            foreach ($availableRoles as $availableRoleForSet) {
                $payload = $this->getRequestPayload($availableRoleForSet);

                // Should be not same $availableRoleForSet and $availableUserRole
                foreach (array_diff($availableRoles, [$availableRoleForSet]) as  $availableUserRole) {
                    $endpoint = $this->getEndpoint($availableUserRole);

                    $response = $this->actingAs($person)->patch($endpoint, $payload);

                    $response->assertStatus(200)
                             ->assertJson($this->getSuccessfulResponse());
                }
            }
        }
    }

    /**
     * @param string $role
     * @param string $adminPassword
     * @return array
     */
    private function getRequestPayload(string $role, string $adminPassword = 'password'): array
    {
        return [
            'role'          => $role,
            'admin_password' => $adminPassword,
        ];
    }

    /**
     * @return bool[]
     */
    private function getSuccessfulResponse(): array
    {
        return ['status' => true];
    }

    /**
     * @return array
     */
    private function getAvailableRolesForAdmin(): array
    {
        return array_filter($this->roles, fn($value) => $value !== 'admin');
    }

    /**
     * @param string $role
     * @return string
     */
    private function getEndpoint(string $userRole = 'user'): string
    {
        $user = User::factory()->getUser(role: $userRole);
        return route('admin.update.user.role', ['user' => $user->id]);
    }

    /**
     * @return array
     */
    private function mapUnavailableRoles(): array
    {
        $arr = [];

        foreach ($this->mappedAvailableRolesForUpdate as $personRole => $availableRoles) {
            $arr[$personRole] = array_diff($this->roles, $availableRoles);
        }

        return $arr;
    }
}
