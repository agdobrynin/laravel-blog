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
        $response->assertRedirect('/en');
    }

    public function testHomeLocaleRuWelcomeText(): void
    {
        $response = $this->get('/ru');

        $response->assertStatus(200);
        $response->assertSeeText('Записи в блоге');
    }

    public function testHomeLocaleEnWelcomeText(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSeeText('Blog post list');
    }
}
