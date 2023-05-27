<?php
declare(strict_types=1);

namespace App\Dto\Request\Api;

readonly class PaginatorDto
{
    public ?int $perPage;
    public ?int $page;

    public function __construct(?string $perPage = null, ?string $page = null)
    {
        $this->perPage = $perPage ? (int)$perPage : null;
        $this->page = $page ? (int)$page : null;
    }
}
