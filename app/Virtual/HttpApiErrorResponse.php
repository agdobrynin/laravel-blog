<?php
declare(strict_types=1);

namespace App\Virtual;

use App\Dto\Response\Api\ApiErrorResponseDto;
use Attribute;
use OpenApi\Attributes as OA;
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class HttpApiErrorResponse extends OA\Response
{
    public function __construct(int $response, string $description = 'Error')
    {
        parent::__construct(
            response: $response,
            description: $description,
            content: [new OA\JsonContent(ref: ApiErrorResponseDto::class)],
        );
    }
}
