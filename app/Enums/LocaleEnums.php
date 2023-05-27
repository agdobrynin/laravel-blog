<?php

namespace App\Enums;

use App\Attribute\Description;
use OpenApi\Attributes as OA;

#[OA\Schema(title: 'Available locales in application')]
#[OA\HeaderParameter(
    name: 'Accept-Language',
    schema: new OA\Schema(
        description: 'Application locale',
        type: 'string',
        default: 'en',
        enum: ['en', 'ru'],
        example: 'en',
    ),
)]
enum LocaleEnums: string
{
    #[Description('English')]
    case EN = 'en';
    #[Description('Русский')]
    case RU = 'ru';
}
