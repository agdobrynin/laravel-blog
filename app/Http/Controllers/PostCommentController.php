<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Jobs\NotifyUsersWatchedCommentable;
use App\Jobs\SendEmails;
use App\Mail\CommentPublishNotifyOwner;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function store(BlogPost $post, StoreCommentRequest $request)
    {
        /** @var Comment $comment */
        $comment = $post->commentsOn()->save(
            new Comment(
                [
                    'content' => $request->input('content'),
                    'user_id' => $request->user()?->id
                ]
            )
        );

        if ($comment->id) {
            SendEmails::dispatch(new CommentPublishNotifyOwner($comment), $post->user);
            NotifyUsersWatchedCommentable::dispatch($comment);

            return redirect()
                ->route('posts.show', $post)
                ->with('success', trans('comment.add.success'));
        }

        return back()->with('error', trans('error.ups'));
    }
}
