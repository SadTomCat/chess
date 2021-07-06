<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register()
    {
        $user = [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];

        $userAfterRegistration = [
            'id'      => 1,
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'blocked' => false,
            'role'    => 'user',
        ];

        $response = $this->post('/register', $user);


        $this->assertAuthenticated();
        $response->assertJson([
            'status' => true,
            'user'   => $userAfterRegistration
        ]);
    }
}
