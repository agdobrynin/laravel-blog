<?php
declare(strict_types=1);

namespace App\Virtual;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class PathParameterCommentId extends OA\PathParameter
{
    public function __construct()
    {
        parent::__construct(
            name: 'comment',
            description: 'Comment Id',
            required: true,
            schema: new OA\Schema(type: 'integer'),
        );
    }
}

