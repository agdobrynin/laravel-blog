<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\CacheTagsEnum;
use App\Models\Tag;
use App\Services\Contracts\TagsDictionaryInterface;
use Illuminate\Cache\TaggedCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSimple;

class TagsDictionary implements TagsDictionaryInterface
{
    public function __construct(protected ?TaggedCache $cache = null)
    {
    }

    /**
     * @return Collection<int,Tag>
     */
    public function tags(): Collection
    {
        return $this->getTags();
    }

    /**
     * @return CollectionSimple<int, string>
     */
    public function tagsForForm(): CollectionSimple
    {
        return $this->getTags()
            ->reduce(fn(CollectionSimple $carry, Tag $item) => $carry->put($item->id, $item->name), collect());
    }

    private function getTags()
    {
        $query = Tag::orderBy('name', 'asc');

        if ($this->cache) {
            return $this->cache->remember(CacheTagsEnum::TAGS->value, null, fn() => $query->get());
        }

        return $query->get();
    }
}
