<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\Request\Api\PostCommentsIndexRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogPost $post, PostCommentsIndexRequestDto $request): AnonymousResourceCollection
    {
        return CommentResource::collection(
            $post->commentsOn()->with('user')
                ->paginate($request->perPage)
                ->appends((array)$request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
