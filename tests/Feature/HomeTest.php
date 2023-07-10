<?php

namespace Tests\Feature;

use Generator;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /** @dataProvider dataForLocale */
    public function testHomeLocale(string $locale, string $text, int $statusCode, ?string $redirectUrl = null): void
    {
        $response = $this->get('/' . $locale)
            ->assertStatus($statusCode);

        $redirectUrl ? $response->assertRedirect($redirectUrl) : $response->assertSeeText($text);
    }

    public function dataForLocale(): Generator
    {
        yield 'ru locale' => ['ru', 'Записи в блоге', 200];
        yield 'en locale' => ['en', 'Blog post list', 200];
        yield 'redirect to' => ['', '', 302, '/en'];
    }
}
