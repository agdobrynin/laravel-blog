<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;

class PostCommentController extends Controller
{
    public function store(BlogPost $post, StoreCommentRequest $request)
    {
        $comment = $post->comments()->save(
            new Comment(
                [
                    'content' => $request->input('content'),
                    'user_id' => $request->user()?->id
                ]
            )
        );

        if ($comment->id) {
            return redirect()
                ->route('posts.show', $post)
                ->with('success', trans('Комментарий успешно добавлен.'));
        }

        return back()->with('error', trans('Что-то пошло не так!'));
    }
}
