<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\Request\Api\ApiPostCommentsIndexDto;
use App\Dto\Request\CommentDto;
use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPostCommentsIndexRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BlogPost $post, ApiPostCommentsIndexRequest $request): AnonymousResourceCollection
    {
        $dto = new ApiPostCommentsIndexDto(...$request->validated());

        return CommentResource::collection(
            $post->commentsOn()->with('user')
                ->paginate($dto->perPage)
                ->appends((array)$request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPost $post, CommentRequest $request): JsonResource
    {
        $dto = new CommentDto(...$request->validated(), user: $request->user());

        $comment = new Comment();
        $comment->content = $dto->content;

        if ($dto->user) {
            $comment->user()->associate($dto->user);
        }

        $post->commentsOn()->save($comment);

        event(new CommentPosted($comment));

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $post, Comment $comment): JsonResource
    {
        return (new CommentResource($comment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPost $post, Comment $comment, CommentRequest $request): JsonResource
    {
        $dto = new CommentDto(...$request->validated());
        $comment->content = $dto->content;
        $comment->save();

        return (new CommentResource($comment));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $post, Comment $comment): Response
    {
        $comment->delete();

        return response()->noContent();
    }
}
