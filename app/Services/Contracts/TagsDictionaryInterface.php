<?php

namespace App\Services\Contracts;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSimple;

interface TagsDictionaryInterface
{
    /**
     * @return Collection<Tag>
     */
    public function tags(): Collection;

    /**
     * Key of collection tag id, value is name of tag.
     *
     * @return CollectionSimple<int,string>
     */
    public function tagsForForm(): CollectionSimple;
}
