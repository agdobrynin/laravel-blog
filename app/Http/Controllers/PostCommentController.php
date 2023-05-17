<?php

namespace App\Http\Controllers;

use App\Dto\Request\CommentDto;
use App\Events\CommentPosted;
use App\Http\Requests\StoreCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class PostCommentController extends Controller
{
    public function store(string $locale, BlogPost $post, CommentDto $dto): RedirectResponse
    {
        $comment = new Comment();
        $comment->content = $dto->content;

        if ($dto->user) {
            $comment->user()->associate($dto->user);
        }

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
