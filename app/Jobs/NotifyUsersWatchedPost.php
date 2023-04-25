<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Mail\CommentPostedOnWatchedPost;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyUsersWatchedPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Comment $comment)
    {
        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $post = $this->comment->commentable()->first();

        User::usersCommentPost($post)
            ->with('image')
            ->get()
            ->map(function (User $user) {
                if ($user->id !== $this->comment->user()->first()?->id) {
                    Mail::to($user)->send(
                        new CommentPostedOnWatchedPost($user, $this->comment)
                    );
                 }
            });
    }
}
