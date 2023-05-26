<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(title: 'Validation item errors')]
readonly class ApiValidationItemDto
{
    public function __construct(
        #[SWG\Property(
            description: 'Key of error display as field name',
            items: new SWG\Items(type: 'string', collectionFormat: 'multi'),
            example: ['some errors 1', 'some errors 2']
        )]
        public array $fieldName
    )
    {
    }
}
