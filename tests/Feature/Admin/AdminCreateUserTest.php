<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class AdminCreateUserTest extends ApiTestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private string $endpoint;

    private array $roles;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = route('admin.users.create');

        $this->roles = config('enums.user_roles');
        $this->assertNotEmpty($this->roles);
    }

    public function test_unauthenticated(): void
    {
        $payload = $this->getRequestPayload('password', 'user');

        $response = $this->post($this->endpoint, $payload);

        $response->assertStatus(401);
    }

    public function test_status_unauthorized_person(): void
    {
        $unauthorizedPersonRoles = array_diff($this->roles, ['admin']);

        foreach ($unauthorizedPersonRoles as $unauthorizedPersonRole) {
            $user = User::factory()->getUser('password', $unauthorizedPersonRole);

            foreach ($this->roles as $role) {
                $payload = $this->getRequestPayload('password', $role);

                $response = $this->actingAs($user)->post($this->endpoint, $payload);

                $response->assertStatus(403);
            }
        }
    }

    public function test_create_user_by_admin_success(): void
    {
        $admin = User::factory()->getUser('password', 'admin');

        $accessRoles = $this->getAccessRolesForSet();
        $this->assertNotEmpty($accessRoles);

        foreach ($accessRoles as $accessRole) {
            $payload = $this->getRequestPayload('password', $accessRole);

            $response = $this->actingAs($admin)->post($this->endpoint, $payload);

            $response->assertStatus(200)
                     ->assertJson($this->getSuccessfulResponse());
        }

        // Try without name
        $payload = $this->getRequestPayload('password', 'user', false);
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);

        $response->assertStatus(200)
                 ->assertJson($this->getSuccessfulResponse());
    }

    public function test_create_user_by_admin_invalid_data(): void
    {
        $admin = User::factory()->getUser('password', 'admin');

        $accessRoles = $this->getAccessRolesForSet();
        $this->assertNotEmpty($accessRoles);

        // Try set admin
        $payload = $this->getRequestPayload('password', 'admin');
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);
        $response->assertStatus(422);

        // Try not exist role
        $payload = $this->getRequestPayload('password', 'aboba');
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);
        $response->assertStatus(422);

        // Exists email
        $user = User::factory()->create();
        $payload = $this->getRequestPayload('password', 'user');
        $payload['email'] = $user->email;
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);
        $response->assertStatus(422);

        // Incorrect admin password
        $payload = $this->getRequestPayload('passeword', 'user');
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);
        $response->assertStatus(422);

        // Without admin password
        $payload = $this->getRequestPayload('password', 'user');
        $payload = array_diff_key($payload, ['admin_password' => 1]);
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);
        $response->assertStatus(422);

        // Without password
        $payload = $this->getRequestPayload('password', 'user');
        $payload = array_diff_key($payload, ['password' => 1]);
        $response = $this->actingAs($admin)->post($this->endpoint, $payload);
        $response->assertStatus(422);
    }

    /**
     * @param string $adminPassword
     * @param string $role
     * @param bool $needName
     * @return array
     */
    private function getRequestPayload(string $adminPassword, string $role, bool $needName = true): array
    {
        $base = [
            'email'         => Factory::create()->unique()->email,
            'password'      => 'password',
            'role'          => $role,
            'admin_password' => $adminPassword,
        ];

        return $needName ? array_merge($base, ['name' => 'Test user']) : $base;
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
    private function getAccessRolesForSet(): array
    {
        return array_filter($this->roles, fn($value) => $value !== 'admin');
    }
}
