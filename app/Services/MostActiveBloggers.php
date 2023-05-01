<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\MostActiveBloggersInterface;
use Illuminate\Cache\TaggedCache;
use Illuminate\Database\Eloquent\Collection;

readonly class MostActiveBloggers implements MostActiveBloggersInterface
{
    public function __construct(
        protected int          $take,
        protected int          $minCountPost,
        protected ?int         $cacheTtl,
        protected ?int         $lastMonth,
        protected ?TaggedCache $cache,
    )
    {
    }

    public function getMinCountPost(): int
    {
        return $this->minCountPost;
    }

    public function getLastMonth(): ?int
    {
        return $this->lastMonth;
    }

    public function get(): Collection
    {
        if ($this->cache) {
            return $this->cache->remember(
                MostActiveBloggersInterface::class . $this->take,
                $this->cacheTtl,
                fn() => User::withMostBlogPostLastMonth($this->lastMonth, $this->minCountPost)
                    ->take($this->take)->get()
            );
        }

        return User::withMostBlogPostLastMonth($this->lastMonth, $this->minCountPost)
            ->take($this->take)->get();
    }
}
