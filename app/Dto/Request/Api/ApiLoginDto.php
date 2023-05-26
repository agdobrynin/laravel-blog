<?php

declare(strict_types=1);

namespace App\Dto\Request\Api;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'ApiLoginDto',
    title: 'Request body for get access token',
    properties: [
        new Property(property: 'email', type: 'string'),
        new Property(property: 'password', type: 'string'),
        new Property(property: 'device', description: 'Device name', type: 'string'),
    ],
)]
readonly class ApiLoginDto
{
    public function __construct(public string $email, public string $password, public string $device)
    {
    }
}
