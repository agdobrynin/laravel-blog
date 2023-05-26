<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;

#[SWG\Schema(
    schema: 'ApiValidationDto',
    title: 'Validation errors',
    properties: [
        new SWG\Property(property: 'message', description: 'The given data was invalid.', type: 'string'),
        new SWG\Property(
            property: 'errors',
            properties: [
                new SWG\Property(
                    property: 'fieldName',
                    description: 'Key of error display as field name',
                    type: 'array',
                    items: new SWG\Items(type: 'string'),
                    collectionFormat: 'multi',
                ),
            ],
            type: 'object',
            example: [
                'email' => [
                    'The email field is required.',
                    'The email must be a valid email address.',
                ],
                'password' => [
                    'The email field is required.',
                ]
            ],
        ),
    ],
)]
class ApiValidationDto
{
    /*
    This is Virtual class for swagger documentation.
    */
}
