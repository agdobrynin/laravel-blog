<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\CacheTagsEnum;
use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;

class TagsDictionaryCache
{
    public static function init(?int $ttl = null): TaggedCache
    {
        return Cache::setDefaultCacheTime($ttl)->tags(CacheTagsEnum::TAGS->value);
    }

    public static function flush(): bool
    {
        return Cache::tags(CacheTagsEnum::TAGS->value)->flush();
    }
}
