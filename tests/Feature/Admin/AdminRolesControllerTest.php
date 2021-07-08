<?php

namespace Tests\Feature\Admin;

use App\Helpers\RolesHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class AdminRolesControllerTest extends ApiTestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private string $endpoint;

    private array $roles;

    private RolesHelper $rolesHelper;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = route('admin.get.access.roles');
        $this->roles = config('enums.user_roles');
        $this->rolesHelper = app(RolesHelper::class);

        $this->assertNotEmpty($this->roles);
    }

    public function test_unauthenticated(): void
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(401);
    }

    public function test_unauthorized(): void
    {
        $unauthorizedPersonRoles = $this->rolesHelper->getUnauthorizedEditors();

        foreach ($unauthorizedPersonRoles as $unauthorizedPersonRole) {
            $unauthorizedPerson = User::factory()->getUser('password', $unauthorizedPersonRole);

            $response = $this->actingAs($unauthorizedPerson)->get($this->endpoint);
            $response->assertStatus(403);
        }
    }

    public function test_authorized(): void
    {
        $authorizedPersonRoles = $this->rolesHelper->getAuthorizedEditors();

        foreach ($authorizedPersonRoles as $authorizedPersonRole) {
            $authorizedPerson = User::factory()->getUser('password', $authorizedPersonRole);

            $response = $this->actingAs($authorizedPerson)->get($this->endpoint);

            $response->assertJson(['roles' => $this->rolesHelper->getAvailableRolesByRole($authorizedPersonRole)])
                     ->assertStatus(200);
        }
    }
}
