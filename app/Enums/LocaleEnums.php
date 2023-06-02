<?php

namespace App\Enums;

use App\Attribute\Description;

enum LocaleEnums: string
{
    #[Description('English')]
    case EN = 'en';
    #[Description('Русский')]
    case RU = 'ru';
}
