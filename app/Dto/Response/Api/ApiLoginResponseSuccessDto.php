<?php

declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(
    schema: 'ApiLoginResponseSuccessDto',
    title: 'Response for success get access token',
    properties: [
        new SWG\Property(property: 'token', type: 'string', example: '5|YEjTnntLJZDlMPrRh1haTU9BLyWfdzIXHoNddFj5'),
        new SWG\Property(property: 'type', type: 'string', example: 'Bearer'),
        new SWG\Property(property: 'device', type: 'string', example: 'Samsung A32'),
    ],
)]
final readonly class ApiLoginResponseSuccessDto
{
    public function __construct(public string $token, public string $device, public string $type = 'Bearer')
    {
    }
}
