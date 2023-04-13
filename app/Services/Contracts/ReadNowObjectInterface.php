<?php

namespace App\Services\Contracts;

interface ReadNowObjectInterface
{
    public function readNowCount(string|int $objectIdentification, string|int $userIdentification): int;
}
