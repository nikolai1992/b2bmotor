<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizationTest extends TestCase
{
    public function testNotAuthorized()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
