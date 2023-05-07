<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted;
use App\Http\Requests\StoreCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;

class PostCommentController extends Controller
{
    public function store(string $locale, BlogPost $post, StoreCommentRequest $request)
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
            event(new CommentPosted($comment));

            return redirect()
                ->route('posts.show', $post)
                ->with('success', trans('comment.add.success'));
        }

        return back()->with('error', trans('error.ups'));
    }
}
