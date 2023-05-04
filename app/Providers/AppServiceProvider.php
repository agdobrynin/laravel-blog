<?php

namespace App\Providers;

use App\Enums\CacheTagsEnum;
use App\Services\CacheStatQueueConfig;
use App\Services\Contracts\MostActiveBloggersInterface;
use App\Services\Contracts\ReadNowObjectInterface;
use App\Services\Contracts\TagsDictionaryInterface;
use App\Services\MostActiveBloggers;
use App\Services\ReadNowObject;
use App\Services\SendEmailsJobConfig;
use App\Services\TagsDictionary;
use App\Services\TagsDictionaryCache;
use Illuminate\Foundation\Application;
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

        $this->app->bind(
            MostActiveBloggersInterface::class,
            fn(Application $app) => $app->make(
                MostActiveBloggers::class,
                [
                    'lastMonth' => config('most_active_bloggers.last_month'),
                    'minCountPost' => config('most_active_bloggers.min_count_post'),
                    'take' => config('most_active_bloggers.take'),
                    'cacheTtl' => config('most_active_bloggers.cache_ttl'),
                    'cache' => config('most_active_bloggers.cache_ttl')
                        ? Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)
                        : null,
                ]
            )
        );

        $this->app->bind(ReadNowObjectInterface::class, ReadNowObject::class);

        $this->app->bind(TagsDictionaryInterface::class, function () {
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
