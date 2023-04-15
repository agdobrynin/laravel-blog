<?php
declare(strict_types=1);

namespace App\View\Composers;

use App\Dto\MostActiveBloggerDto;
use App\Enums\CacheTagsEnum;
use App\Services\Contracts\MostActiveBloggersInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MostActiveBloggersComposer
{
    public function __construct(protected MostActiveBloggersInterface $mostActiveBloggers)
    {
    }

    public function compose(View $view): void
    {
        $takeMostActiveBloggers = env('MOST_ACTIVE_BLOGGER_TAKE_USERS', 5);
        $bloggers = Cache::tags([
            CacheTagsEnum::BLOG_INDEX->value,
            CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value
        ])->remember(
            MostActiveBloggersInterface::class . $takeMostActiveBloggers,
            env('MOST_ACTIVE_BLOGGER_CACHE_TTL', 1800)
            , fn() => $this->mostActiveBloggers->get($takeMostActiveBloggers)
        );

        $mostActiveBloggerDto = new MostActiveBloggerDto(
            bloggers: $bloggers,
            lastMonth: $this->mostActiveBloggers->getLastMonth(),
            minCountPost: $this->mostActiveBloggers->getMinCountPost(),
        );

        $view->with('mostActiveBloggerDto', $mostActiveBloggerDto);
    }
}
