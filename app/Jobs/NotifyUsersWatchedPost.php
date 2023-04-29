<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Mail\CommentPostedOnWatchedPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUsersWatchedPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Comment $comment)
    {
        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    public function handle(): void
    {
        $post = $this->comment->commentable()->first();

        User::usersCommentPost($post)
            ->with('image')
            ->get()
            ->map(function (User $user) {
                if ($user->id !== $this->comment->user()->first()?->id) {
                    SendEmails::dispatch(new CommentPostedOnWatchedPost($user, $this->comment), $user);
                }
            });
    }
}
