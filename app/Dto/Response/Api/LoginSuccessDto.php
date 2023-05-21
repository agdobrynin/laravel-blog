<?php

declare(strict_types=1);

namespace App\Dto\Response\Api;

final readonly class LoginSuccessDto
{
    public function __construct(public string $token, public string $type = 'Bearer')
    {
    }
}
