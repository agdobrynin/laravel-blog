<?php

namespace App\Providers;

use App\Events\BlogPostAdded;
use App\Events\CommentPosted;
use App\Events\ResizeAvatarImageEvent;
use App\Events\ResizeBlogPostImageEvent;
use App\Listeners\CacheStatSubscriber;
use App\Listeners\NotifyAdminBlogPostAdded;
use App\Listeners\NotifyUsersAboutComment;
use App\Listeners\ResizeAvatarImageSubscriber;
use App\Listeners\ResizeBlogPostImageSubscriber;
use App\Listeners\UserEventSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentPosted::class => [
            NotifyUsersAboutComment::class,
        ],
        BlogPostAdded::class => [
            NotifyAdminBlogPostAdded::class,
        ],
        ResizeAvatarImageEvent::class => [
            ResizeAvatarImageSubscriber::class,
        ],
        ResizeBlogPostImageEvent::class => [
            ResizeBlogPostImageSubscriber::class,
        ],
    ];

    protected $subscribe = [
        CacheStatSubscriber::class,
        UserEventSubscriber::class,
    ];
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
