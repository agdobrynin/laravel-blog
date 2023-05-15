<?php
declare(strict_types=1);

namespace App\Services;

use App\Services\Contracts\ReadNowObjectInterface;
use Illuminate\Cache\TaggedCache;
use Illuminate\Database\Eloquent\Model;

readonly class ReadNowObjectByRedisWithTags implements ReadNowObjectInterface
{
    private const CACHE_KEY = 'read-object-by-users:';

    public function __construct(private int $minutesTimeout, private TaggedCache $cache)
    {
    }

    public function readNowCount(Model $object, string|int $userIdentification): int
    {
        $cacheUsersKey = self::CACHE_KEY . $object->getKey() . ':' . $object::class;
        $readers = $this->cache->get($cacheUsersKey, []);

        $now = now();
        $readers[$userIdentification] = $now;

        foreach ($readers as $userIdentificationKey => $lastVisit) {
            if ($this->minutesTimeout <= $now->diffInMinutes($lastVisit)) {
                unset($readers[$userIdentificationKey]);
            }
        }

        $this->cache->forever($cacheUsersKey, $readers);

        return count($readers);
    }
}
