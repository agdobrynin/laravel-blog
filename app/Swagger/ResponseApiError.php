<?php
declare(strict_types=1);

namespace App\Swagger;

use App\Dto\Response\Api\ApiErrorResponseDto;
use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ResponseApiError',
    description: 'Error message',
    content: [new OA\JsonContent(ref: ApiErrorResponseDto::class)],
)]
class ResponseApiError
{
}
