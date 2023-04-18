<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\CacheTagsEnum;
use App\Services\Contracts\ReadNowObjectInterface;
use Illuminate\Support\Facades\Cache;

readonly class ReadNowObject implements ReadNowObjectInterface
{
    public function readNowCount(int|string $objectIdentification, string|int $userIdentification): int
    {
        $cacheUsersKey = 'read-object-users:' . $objectIdentification;

        $readers = Cache::tags(CacheTagsEnum::READ_NOW_OBJECT->value)->get($cacheUsersKey, []);

        $now = now();
        $readers[$userIdentification] = $now;

        foreach ($readers as $userIdentificationKey => $lastVisit) {
            if (1 <= $now->diffInMinutes($lastVisit)) {
                unset($readers[$userIdentificationKey]);
            }
        }

        Cache::tags(CacheTagsEnum::READ_NOW_OBJECT->value)->forever($cacheUsersKey, $readers);

        return count($readers);
    }
}
