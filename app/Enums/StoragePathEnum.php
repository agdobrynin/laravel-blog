<?php

namespace App\Enums;

enum StoragePathEnum: string
{
    case POST_THUMBNAIL = 'post_thumbs';
    case USER_AVATAR = 'avatars';
}
