<?php
declare(strict_types=1);

namespace App\Swagger;

use App\Http\Resources\CommentResource;
use OpenApi\Attributes as OA;
#[OA\Schema(
    description: 'List of Blog post comments with pagination',
    properties: [
        new OA\Property(
            property: 'data', type: 'array', items: new OA\Items(ref: CommentResource::class)
        )
    ],
    allOf: [
        new OA\Schema(ref: Paginate::class),
    ]
)]
class CommentResourceCollection
{

}
