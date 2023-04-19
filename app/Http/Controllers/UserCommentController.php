<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
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
    public function store(User $user, StoreCommentRequest $request): RedirectResponse
    {
        $comment = $user->commentsOn()->save(
            new Comment(
                [
                    'content' => $request->input('content'),
                    'user_id' => $request->user()->id
                ]
            )
        );

        if ($comment->id) {
            return redirect()->route('users.show', $user)
                ->with('success', trans('comment.add.success'));
        }

        return back()->with('error', trans('error.ups'));
    }
}
