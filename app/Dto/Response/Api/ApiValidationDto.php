<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as OA;

#[OA\Schema(title: 'Validation errors')]
readonly class ApiValidationDto
{
    /*
    This is Virtual class for swagger documentation.
    */
    public function __construct(
        #[OA\Property(description: 'The given data was invalid.')]
        public string $message,
        #[OA\Property(ref: ApiValidationItemDto::class)]
        public mixed $errors,
    )
    {
    }
}
