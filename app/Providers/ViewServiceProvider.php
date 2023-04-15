<?php

namespace App\Providers;

use App\View\Composers\MostActiveBloggersComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['post.list', 'post.show'], MostActiveBloggersComposer::class);
    }
}
