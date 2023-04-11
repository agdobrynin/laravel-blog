<?php

namespace App\Enums;

enum OrderBlogPostEnum: string
{
    case LATEST_UPDATED = 'Сначала новые';
    case MOST_COMMENTED = 'Сначала самые обсуждаемые';
}
