<?php

namespace App\Http\Controllers;

use App\Dto\CommentDto;
use App\Events\CommentPosted;
use App\Http\Requests\StoreCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;

class PostCommentController extends Controller
{
    public function store(string $locale, BlogPost $post, StoreCommentRequest $request)
    {
        $dto = new CommentDto($request);

        $comment = new Comment();
        $comment->content = $dto->content;
        $comment->user()->associate($dto->user);
        $post->commentsOn()->save($comment);

        if ($comment->id) {
            event(new CommentPosted($comment));

            return redirect()
                ->route('posts.show', $post)
                ->with('success', trans('comment.add.success'));
        }

        return back()->with('error', trans('error.ups'));
    }
}
