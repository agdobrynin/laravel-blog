<?php

namespace App\Enums;

enum OrderBlogPostEnum: string
{
    case LATEST_UPDATED = 'Сначала новые посты';
    case MOST_COMMENTED = 'Сначала самые обсуждаемые посты';
}
