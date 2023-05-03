<?php

namespace App\Listeners;

use App\Enums\QueueNamesEnum;
use App\Enums\RolesEnum;
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
        $admins = Role::where('slug', RolesEnum::ADMIN->value)->with('users')->first();
        Mail::to($admins->users)->send(new NotifyBlogPostAddedMail($event->post));
    }
}
