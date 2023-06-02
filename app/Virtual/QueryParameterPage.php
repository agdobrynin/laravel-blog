<?php
declare(strict_types=1);

namespace App\Virtual;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class QueryParameterPage extends OA\QueryParameter
{
    public function __construct()
    {
        parent::__construct(
            name: 'page',
            description: 'Current page',
            required: false,
            schema: new OA\Schema(type: 'integer', default: 1)
        );
    }
}

