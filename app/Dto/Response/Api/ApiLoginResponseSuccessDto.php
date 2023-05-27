<?php

declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as OA;

#[OA\Schema(title: 'Response for success get access token')]
final readonly class ApiLoginResponseSuccessDto
{
    public function __construct(
        #[OA\Property(example: '5|YEjTnntLJZDlMPrRh1haTU9BLyWfdzIXHoNddFj5')]
        public string $token,
        #[OA\Property(example: 'swagger ui')]
        public string $device,
        #[OA\Property(example: 'Bearer')]
        public string $type = 'Bearer',
    )
    {
    }
}
