<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as OA;

#[OA\Schema(title: 'Validation item errors')]
readonly class ApiValidationItemDto
{
    public function __construct(
        #[OA\Property(
            description: 'Key of error display as field name',
            items: new OA\Items(type: 'string'),
            example: ['some errors 1', 'some errors 2']
        )]
        public array $fieldName
    )
    {
    }
}
