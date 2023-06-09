<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUsersWatchedCommentable;
use App\Jobs\SendEmails;
use App\Mail\CommentPublishNotifyOwner;
use App\Models\BlogPost;
use App\Models\User;

class NotifyUsersAboutComment
{
    public function handle(CommentPosted $event): void
    {
        /** @var User $owner */
        $owner = match ($event->comment->commentable_type) {
            User::class => $event->comment->commentable,
            BlogPost::class => $event->comment->commentable->user
        };

        $mail = new CommentPublishNotifyOwner($event->comment, $owner->locale());
        SendEmails::dispatch($mail, $owner);
        NotifyUsersWatchedCommentable::dispatch($event->comment);
    }
}
