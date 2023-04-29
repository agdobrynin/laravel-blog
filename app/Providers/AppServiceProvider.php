<?php

namespace App\Providers;

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
use Illuminate\Support\Facades\App;
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
                    'lastMonth' => env('MOST_ACTIVE_BLOGGER_LAST_MONTH'),
                    'minCountPost' => env('MOST_ACTIVE_BLOGGER_MIN_POSTS', 5),
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
            extract(config('queue.jobs.send_emails'));

            return new SendEmailsJobConfig(
                maxLocks: $max_locks,
                releaseDelay: $release_delay,
                timeLock: $time_lock
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
