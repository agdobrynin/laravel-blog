<?php

namespace Tests\Feature;

use App\Models\User;
use Closure;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider dataAuth */
    public function testAuth(Closure $payload, int $status, array $jsonStructure, array $json = []): void
    {
        $response = $this->postJson(
            '/api/take-token',
            $payload()
        )
            ->assertStatus($status)
            ->assertJsonStructure($jsonStructure);

        if ($json) {
            $response->assertJson($json);
        }
    }

    public function dataAuth(): Generator
    {
        yield 'success take token' => [
            fn () => ['email' => User::factory()->create()->email, 'password' => 'password', 'device' => 'test'],
            200,
            ['token', 'type'],
        ];

        yield 'failed credential' => [
            fn() => ['email' => 'aaa@aaa.com', 'password' => 'password', 'device' => 'test'],
            403,
            ['message']
        ];

        yield 'validation error' => [
            fn() => [],
            422,
            ['message', 'errors' => ['email', 'password', 'device']],
            ['message' => 'The email field is required. (and 2 more errors)']
        ];
    }
}
