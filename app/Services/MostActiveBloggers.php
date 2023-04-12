<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\MostActiveBloggersInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class MostActiveBloggers implements MostActiveBloggersInterface
{
    public function __construct(
        protected ?int $minCountPost,
        protected ?int $lastMonth,
    )
    {
    }

    public function getMinCountPost():? int
    {
        return $this->minCountPost;
    }

    public function getLastMonth(): ?int
    {
        return $this->minCountPost;
    }

    public function get(int $take): Collection
    {
        return User::withMostBlogPostLastMonth($this->lastMonth, $this->minCountPost)
            ->take($take)
            ->get();
    }

    public function getCached(int $take, int $ttl): Collection
    {
        $cacheKey = $this->getCacheKey();

        return Cache::remember($cacheKey, $ttl, fn() => $this->get($take));
    }

    public function getCacheKey(): string
    {
        return sprintf('most-active-bloggers-last_month_%d-post_min_count_%d', $this->lastMonth, $this->minCountPost);
    }
}
