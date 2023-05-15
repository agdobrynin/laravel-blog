<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ReadNowObjectInterface
{
    public function readNowCount(Model $object, string|int $userIdentification): int;
}
