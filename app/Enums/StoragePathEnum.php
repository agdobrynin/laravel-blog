<?php

namespace App\Enums;

enum StoragePathEnum: string
{
    case POST_THUMBNAIL = 'thumbs';
    case USER_AVATAR = 'avatars';
}
