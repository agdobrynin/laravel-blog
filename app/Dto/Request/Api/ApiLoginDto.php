<?php
declare(strict_types=1);

namespace App\Dto\Request\Api;

readonly class ApiLoginDto
{
    public function __construct(
        public string $email,
        public string $password,
        public string $device,
    )
    {
    }
}
