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

        $this->postJson(
            '/api/login',
            ['email' => $user->email, 'password' => 'password', 'device' => 'test']
        )
            ->assertOk()
            ->assertJsonStructure(['token', 'type']);
    }

    public function testAuthFailedCredit(): void
    {
        $this->postJson(
            '/api/login',
            ['email' => 'aaa@aaa.com', 'password' => 'password', 'device' => 'test']
        )
            ->assertForbidden()
            ->assertJson(['message' => 'These credentials do not match our records.']);
    }

    public function testAuthValidationError(): void
    {
        $response = $this->postJson(
            '/api/login',
        )
            ->assertUnprocessable()
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
