<?php

declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(
    schema: 'ApiLoginResponseSuccessDto',
    title: 'Response for success get access token',
)]
final readonly class ApiLoginResponseSuccessDto
{
    public function __construct(
        #[SWG\Property(example: '5|YEjTnntLJZDlMPrRh1haTU9BLyWfdzIXHoNddFj5')]
        public string $token,
        #[SWG\Property(example: 'Samsung A32')]
        public string $device,
        #[SWG\Property(example: 'Bearer')]
        public string $type = 'Bearer',
    )
    {
    }
}
