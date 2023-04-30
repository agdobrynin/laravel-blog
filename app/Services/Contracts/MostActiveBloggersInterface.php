<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface MostActiveBloggersInterface
{
    /**
     * Period for calculate most active bloggers.
     *
     * @return int|null
     */
    public function getLastMonth():? int;

    /**
     * Each blogger mas minimum posts
     *
     * @return int|null
     */
    public function getMinCountPost(): int;

    /**
     * @return Collection<User>
     */
    public function get(): Collection;
}
