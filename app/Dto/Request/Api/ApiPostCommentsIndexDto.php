<?php
declare(strict_types=1);

namespace App\Dto\Request\Api;

readonly class ApiPostCommentsIndexDto
{
    public ?int $perPage;
    public function __construct(?string $perPage = null)
    {
        $this->perPage = (int)$perPage;
    }
}
