<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(title: 'Validation errors')]
readonly class ApiValidationDto
{
    /*
    This is Virtual class for swagger documentation.
    */
    public function __construct(
        #[SWG\Property(description: 'The given data was invalid.')]
        public string $message,
        #[SWG\Property(ref: ApiValidationItemDto::class)]
        public mixed $errors,
    )
    {
    }
}
