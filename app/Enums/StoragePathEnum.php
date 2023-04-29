<?php

namespace App\Enums;

enum StoragePathEnum: string
{
    case POST_IMAGE = 'post_images';
    case POST_IMAGE_THUMB = 'thumbs/post_images';
    case USER_AVATAR = 'avatars';
    case USER_AVATAR_THUMB = 'thumbs/avatars';
}
