<?php

namespace App\Providers;

use App\Services\Contracts\MostActiveBloggersInterface;
use App\Services\MostActiveBloggers;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            MostActiveBloggersInterface::class,
            fn(Application $app) => App::make(
                MostActiveBloggers::class,
                [
                    'lastMonth' => env('MOST_ACTIVE_BLOGGER_LAST_MONTH'),
                    'minCountPost' => env('MOST_ACTIVE_BLOGGER_MIN_POSTS', 5),
                ]
            )
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
