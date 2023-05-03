<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Mail\CommentPostedOnWatchedCommentable;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUsersWatchedCommentable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Comment $comment)
    {
        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    public function handle(): void
    {
        User::usersCommentable($this->comment->commentable)
            ->with('image')
            ->get()
            ->map(function (User $user) {
                $owner = match ($this->comment->commentable_type) {
                    BlogPost::class => $this->comment->commentable->user,
                    User::class => $this->comment->commentable,
                };

                if ($user->id !== $owner->id) {
                    SendEmails::dispatch(new CommentPostedOnWatchedCommentable($user, $this->comment), $user);
                }
            });
    }
}
