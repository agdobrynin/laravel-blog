<?php

namespace App\Enums;

enum QueueNamesEnum: string
{
    case DEFAULT = 'default';
    case EMAIL = 'email';
    case LOW = 'low';
}

