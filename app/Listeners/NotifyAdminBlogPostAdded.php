<?php

namespace App\Listeners;

use App\Enums\QueueNamesEnum;
use App\Events\BlogPostAdded;
use App\Mail\NotifyBlogPostAddedMail;
use App\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminBlogPostAdded implements ShouldQueue
{
    public string $queue = QueueNamesEnum::LOW->value;

    public function handle(BlogPostAdded $event): void
    {
        Mail::to('admin@facke.com')->send(new NotifyBlogPostAddedMail($event->post));
    }
}
