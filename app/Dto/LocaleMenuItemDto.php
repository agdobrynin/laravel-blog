<?php
declare(strict_types=1);

namespace App\Dto;

readonly class LocaleMenuItemDto
{
    public function __construct(
        public string $title,
        public string $url,
        public ?string $locale = null,
    )
    {
    }
}
