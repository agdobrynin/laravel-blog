<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(
    schema: 'ApiErrorResponseDto',
    title: 'Response for error',
    properties: [
        new SWG\Property(property: 'message', type: 'string', example: 'Description of what happened'),
        new SWG\Property(property: 'exception', type: 'string', example: 'Exception'),
    ],
)]
readonly class ApiErrorResponseDto
{
    public function __construct(
        public string $message,
        public string $exception,
        public ?string $file = null,
        public ?int $line = null,
    )
    {
    }
}
