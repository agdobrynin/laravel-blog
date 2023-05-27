<?php
declare(strict_types=1);

namespace App\Swagger;

use App\Dto\Response\Api\ApiValidationDto;
use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ResponseApiValidationError',
    description: 'Validation errors',
    content: [new OA\JsonContent(ref: ApiValidationDto::class)]
)]
class ResponseApiValidationError
{

}
