<?php

namespace App\Http\Controllers;

use App\Dto\Request\CommentDto;
use App\Events\CommentPosted;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $locale, User $user, CommentRequest $request): RedirectResponse
    {
        $dto = new CommentDto(...$request->validated(), user: $request->user());
        $comment = new Comment();
        $comment->content = $dto->content;
        $comment->user()->associate($dto->user);

        $user->commentsOn()->save($comment);

        if ($comment->id) {
            event(new CommentPosted($comment));

            return redirect()->route('users.show', $user)
                ->with('success', trans('comment.add.success'));
        }

        return back()->with('error', trans('error.ups'));
    }
}
