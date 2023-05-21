<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuth extends TestCase
{
    use RefreshDatabase;

    public function testAuthSuccess(): void
    {
        $user = User::factory()->create();

        $response = $this->json(
            'POST',
            '/api/login',
            ['email' => $user->email, 'password' => 'password', 'device' => 'test']
        );

        $response->assertOk()
            ->assertJsonStructure(['token', 'type']);
    }

    public function testAuthFailedCredit(): void
    {
        $response = $this->json(
            'POST',
            '/api/login',
            ['email' => 'aaa@aaa.com', 'password' => 'password', 'device' => 'test']
        );

        $response->assertForbidden()
            ->assertJson(['message' => 'These credentials do not match our records.']);
    }

    public function testAuthValidationError(): void
    {
        $response = $this->json(
            'POST',
            '/api/login',
        );

        $response->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password',
                    'device'
                ]
            ])
            ->assertJson(['message' => 'The email field is required. (and 2 more errors)']);
    }
}
