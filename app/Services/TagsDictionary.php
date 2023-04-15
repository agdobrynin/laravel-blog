<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;
use App\Services\Contracts\TagsDictionaryInterface;
use Illuminate\Cache\TaggedCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSimple;

class TagsDictionary implements TagsDictionaryInterface
{
    protected Collection $tags;

    public function __construct(?TaggedCache $cache = null)
    {
        $query = Tag::orderBy('name', 'asc');

        if ($cache) {
            $this->tags = $cache->get(Tag::class, fn() => $query->get());
        } else {
            $this->tags = $query->get();
        }
    }

    public function tags(): Collection
    {
        return $this->tags;
    }

    public function tagsForForm(): CollectionSimple
    {
        return $this->tags
            ->reduce(fn(CollectionSimple $carry, Tag $item) => $carry->put($item->id, $item->name), collect());
    }
}
