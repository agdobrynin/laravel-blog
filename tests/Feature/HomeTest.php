<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testHomePageWelcomeText(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('Welcome to Laravel App');
    }
}
