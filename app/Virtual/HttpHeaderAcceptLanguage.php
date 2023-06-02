<?php
declare(strict_types=1);

namespace App\Virtual;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class HttpHeaderAcceptLanguage extends OA\HeaderParameter
{
    public function __construct()
    {
        parent::__construct(
            name: 'Accept-Language',
            schema: new OA\Schema(
                description: 'Application locale',
                type: 'string',
                default: 'en',
                enum: ['en', 'ru'],
                example: 'en',
            ),
        );
    }
}
