<?php

namespace App\Providers;

use App\Dto\Request\BlogPostDto;
use App\Dto\Request\CommentDto;
use App\Dto\Request\UserProfileDto;
use App\Enums\CacheTagsEnum;
use App\Enums\LocaleEnums;
use App\Http\Requests\BlogPostRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\CacheStatQueueConfig;
use App\Services\Contracts\MostActiveBloggersInterface;
use App\Services\Contracts\ReadNowObjectInterface;
use App\Services\Contracts\TagsDictionaryInterface;
use App\Services\LocaleMenu;
use App\Services\MostActiveBloggers;
use App\Services\ReadNowObjectByRedisWithTags;
use App\Services\SendEmailsJobConfig;
use App\Services\TagsDictionary;
use App\Services\TagsDictionaryCache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();

        $this->app->singleton(MostActiveBloggersInterface::class, function () {
            [
                'last_month' => $lastMonth,
                'min_count_post' => $minCountPost,
                'take' => $take,
                'cache_ttl' => $cacheTtl

            ] = config('most_active_bloggers');

            $cache = $cacheTtl ? Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value) : null;

            return new MostActiveBloggers(
                take: $take,
                minCountPost: $minCountPost,
                cacheTtl: $cacheTtl,
                lastMonth: $lastMonth,
                cache: $cache
            );
        });

        $this->app->singleton(ReadNowObjectByRedisWithTags::class, function (Application $app) {
            return new ReadNowObjectByRedisWithTags(
                config('read_now_object.counter_minutes_timeout'),
                Cache::tags(CacheTagsEnum::READ_NOW_OBJECT->value)
            );
        });

        $this->app->bind(ReadNowObjectInterface::class, ReadNowObjectByRedisWithTags::class);

        $this->app->singleton(TagsDictionaryInterface::class, function () {
            $cache = config('tags-dictionary.cache.enabled')
                ? TagsDictionaryCache::init(config('tags-dictionary.cache.ttl'))
                : null;

            return new TagsDictionary($cache);
        });

        $this->app->singleton(SendEmailsJobConfig::class, function () {
            ['max_locks' => $maxLocks, 'time_lock' => $timeLock, 'release_delay' => $releaseDelay] = config('queue.jobs.send_emails');

            return new SendEmailsJobConfig(
                maxLocks: $maxLocks,
                releaseDelay: $releaseDelay,
                timeLock: $timeLock
            );
        });

        $this->app->singleton(CacheStatQueueConfig::class, function () {
            ['max_locks' => $maxLocks, 'time_lock' => $timeLock, 'release_delay' => $releaseDelay] = config('queue.cache_stat_config');

            return new CacheStatQueueConfig(
                maxLocks: $maxLocks,
                releaseDelay: $releaseDelay,
                timeLock: $timeLock
            );
        });

        $this->app
            ->bind(
                LocaleMenu::class,
                fn() => new LocaleMenu(LocaleEnums::EN, LocaleEnums::RU)
            );

        $this->app->bind(
            UserProfileDto::class,
            fn(Application $app) => UserProfileDto::fromRequest($app->make(UserUpdateRequest::class))
        );

        $this->app->bind(
            CommentDto::class,
            fn(Application $app) => CommentDto::fromRequest($app->make(StoreCommentRequest::class))
        );

        $this->app->bind(
            BlogPostDto::class,
            fn(Application $app) => BlogPostDto::fromRequest($app->make(BlogPostRequest::class))
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
