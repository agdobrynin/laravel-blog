<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\CacheTagsEnum;
use App\Models\User;
use App\Services\Contracts\MostActiveBloggersInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class MostActiveBloggers implements MostActiveBloggersInterface
{
    public function __construct(
        protected int $take,
        protected int $minCountPost,
        protected int $cacheTtl,
        protected ?int $lastMonth,
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
        return Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)->remember(
            MostActiveBloggersInterface::class . $this->take,
            $this->cacheTtl,
            fn() => User::withMostBlogPostLastMonth($this->lastMonth, $this->minCountPost)->take($this->take)->get()
        );
    }
}
