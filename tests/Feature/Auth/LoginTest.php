<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testUserCanViewLoginForm()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function testUserCannotViewLoginFormWhenAuthenticated()
    {
        $user = factory(\App\User::class)->make();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/');
    }

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = factory(\App\User::class)->create([
            'password' => bcrypt($password = 'testpass'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}
