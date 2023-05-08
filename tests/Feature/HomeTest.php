<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomeLocaleRedirect(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/ru');
    }

    public function testHomeLocaleWelcomeText(): void
    {
        $response = $this->get('/ru');

        $response->assertStatus(200);
        $response->assertSeeText('Welcome to Laravel App');
    }
}
