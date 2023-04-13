<?php
declare(strict_types=1);

namespace App\Services;

use App\Services\Contracts\ReadNowObjectInterface;
use Illuminate\Support\Facades\Cache;

readonly class ReadNowObject implements ReadNowObjectInterface
{
    public function readNowCount(int|string $objectIdentification, string|int $userIdentification): int
    {
        $cacheUsersKey = 'read-object-users:' . $objectIdentification;
        $readers = Cache::get($cacheUsersKey) ?: [];

        $now = now();

        if (!isset($readers[$userIdentification])) {
            $readers[$userIdentification] = $now;
        } else {
            foreach ($readers as $userIdentificationKey => $lastVisit) {
                if (1 <= $now->diffInMinutes($lastVisit)) {
                    unset($readers[$userIdentificationKey]);
                }
            }

            $readers[$userIdentification] = $now;
        }

        Cache::forever($cacheUsersKey, $readers);

        return count($readers);
    }
}
