<?php
declare(strict_types=1);

namespace App\Services;

readonly class SendEmailsJobConfig
{
    public function __construct(
        public int $maxLocks,
        public int $releaseDelay,
        public int $timeLock
    )
    {
    }
}
