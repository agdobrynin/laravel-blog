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
    public function __construct()
    {
        //
    }

    public function handle(CommentPosted $event): void
    {
        $owner = match($event->comment->commentable_type) {
            User::class => $event->comment->commentable,
            BlogPost::class => $event->comment->commentable->user
        };

        SendEmails::dispatch(new CommentPublishNotifyOwner($event->comment), $owner);
        NotifyUsersWatchedCommentable::dispatch($event->comment);
    }
}
