<?php
declare(strict_types=1);

namespace App\Dto\Request\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(
    title: 'Request body for get access token',
)]
readonly class ApiLoginDto
{
    public function __construct(
        #[SWG\Property]
        public string $email,
        #[SWG\Property]
        public string $password,
        #[SWG\Property(description: 'Set device name')]
        public string $device,
    )
    {
    }
}
