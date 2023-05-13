<?php

namespace App\Listeners;

use App\Enums\QueueNamesEnum;
use App\Enums\RolesEnum;
use App\Events\BlogPostAdded;
use App\Jobs\SendEmails;
use App\Mail\NotifyBlogPostAddedMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminBlogPostAdded implements ShouldQueue
{
    public string $queue = QueueNamesEnum::LOW->value;

    public function handle(BlogPostAdded $event): void
    {
        Role::where('slug', RolesEnum::ADMIN->value)
            ->with('users')
            ->first()
            ?->users
            ->map(fn(User $user) => SendEmails::dispatch(new NotifyBlogPostAddedMail($event->post, $user->locale(), $user), $user));
    }
}
