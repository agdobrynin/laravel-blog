<?php

namespace App\Enums;

use App\Attribute\Description;

enum CacheTagsEnum: string
{
    #[Description('Cached blocks in blog posts index.')]
    case BLOG_INDEX = 'blog_index';

    #[Description('Cached blocks with info about most active bloggers.')]
    case MOST_ACTIVE_BLOGGERS = 'most_active_bloggers';

    #[Description('Cache for user groups uses in user roles.')]
    case USER_GROUP = 'user_group';

    #[Description('Cache statistic blocks for reading now posts and other site items.')]
    case READ_NOW_OBJECT = 'read_now_object';

    #[Description('Cache for blog post tags')]
    case TAGS = 'tags';
}
