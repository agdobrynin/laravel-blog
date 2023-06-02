<?php
declare(strict_types=1);

namespace App\Virtual\Models;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Paginate information',
    properties: [
        new OA\Property(
            property: 'links',
            description: 'Links to different pages in collection. Short version.',
            properties: [
                new OA\Property(
                    property: 'first',
                    description: 'Link to first page in collection',
                    type: 'string',
                    example: 'http://localhost/api/v1/posts/1/comments?perPage=10&page=1'
                ),
                new OA\Property(
                    property: 'last',
                    description: 'Link to last page in collection',
                    type: 'string',
                    example: 'http://localhost/api/v1/posts/1/comments?perPage=10&page=10'
                ),
                new OA\Property(
                    property: 'prev',
                    description: 'Link to previous page from current page in collection',
                    type: 'string',
                    example: 'http://localhost/api/v1/posts/1/comments?perPage=10&page=10',
                    nullable: true,
                ),
                new OA\Property(
                    property: 'next',
                    description: 'Link to next page from current page in collection',
                    type: 'string',
                    example: 'http://localhost/api/v1/posts/1/comments?perPage=10&page=2',
                    nullable: true,
                ),
            ],
        ),
        new OA\Property(
            property: 'meta',
            properties: [
                new OA\Property(
                    property: 'links',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: 'url',
                                type: 'string',
                                example: 'http://localhost/api/v1/posts/1/comments?perPage=10&page=1',
                                nullable: true
                            ),
                            new OA\Property(
                                property: 'label',
                                type: 'string',
                                example: '1',
                                nullable: true
                            ),
                            new OA\Property(
                                property: 'active',
                                type: 'boolean',
                                example: true,
                            ),
                        ]
                    )
                ),
                new OA\Property(
                    property: 'current_page',
                    description: 'Current page',
                    type: 'integer',
                    example: 1,
                ),
                new OA\Property(
                    property: 'path',
                    description: 'Base link',
                    type: 'string',
                    example: 'http://localhost/api/v1/posts/1/comments',
                ),
                new OA\Property(
                    property: 'per_page',
                    description: 'How many items show per page',
                    type: 'integer',
                    example: 10,
                ),
                new OA\Property(
                    property: 'from',
                    description: 'Index value "from" in collection',
                    type: 'integer',
                    example: 1,
                ),
                new OA\Property(
                    property: 'to',
                    description: 'Index value "to" in collection',
                    type: 'integer',
                    example: 10,
                ),
                new OA\Property(
                    property: 'total',
                    description: 'Total items in collection',
                    type: 'integer',
                    example: 95,
                ),
                new OA\Property(
                    property: 'last_page',
                    description: 'Last page in collection',
                    type: 'integer',
                    example: 10,
                ),
            ],
            type: 'object'
        ),
    ]
)]
class Paginate
{
}
