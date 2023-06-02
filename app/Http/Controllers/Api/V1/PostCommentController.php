<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\Request\Api\PaginatorDto;
use App\Dto\Request\CommentDto;
use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPostCommentsPaginatorRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Virtual\HttpApiErrorResponse;
use App\Virtual\HttpHeaderAcceptLanguage;
use App\Virtual\HttpValidationErrorResponse;
use App\Virtual\Models\Paginate;
use App\Virtual\PathParameterCommentId;
use App\Virtual\PathParameterPostId;
use App\Virtual\QueryParameterPage;
use App\Virtual\QueryParameterPerPage;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    #[OA\Get(
        path: '/v1/posts/{post}/comments',
        operationId: 'getCommentsByPostId',
        description: 'Show comments by post id. Comments with pagination.',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
    )]
    #[HttpHeaderAcceptLanguage]
    #[PathParameterPostId]
    #[QueryParameterPerPage]
    #[QueryParameterPage]
    #[OA\Response(
        response: 200,
        description: 'Comments list with pagination for blog post id',
        content: new OA\JsonContent(
            type: 'object',
            allOf: [
                new OA\Schema(ref: CommentResource::class),
                new OA\Schema(ref: Paginate::class),
            ]
        ),
    )]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    #[HttpApiErrorResponse(response: 404, description: 'Not found')]
    #[HttpValidationErrorResponse]
    #[HttpApiErrorResponse(response: 500, description: 'Server error')]
    public function index(BlogPost $post, ApiPostCommentsPaginatorRequest $request): AnonymousResourceCollection
    {
        $dto = new PaginatorDto(...$request->validated());

        return CommentResource::collection(
            $post->commentsOn()->with('user')
                ->paginate($dto->perPage)
                ->appends((array)$dto)
        );
    }

    #[OA\Post(
        path: '/v1/posts/{post}/comments',
        operationId: 'storeCommentToPostById',
        description: 'Store comment to blog post by postId',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
    )]
    #[HttpHeaderAcceptLanguage]
    #[PathParameterPostId]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: CommentRequest::class),
    )]
    #[OA\Response(
        response: 201,
        description: 'Comment was added',
        content: new OA\JsonContent(ref: CommentResource::class)
    )]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    #[HttpApiErrorResponse(response: 404, description: 'Not found')]
    #[HttpApiErrorResponse(response: 500, description: 'Server error')]
    #[HttpValidationErrorResponse]
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

    #[OA\Get(
        path: '/v1/posts/{post}/comments/{comment}',
        operationId: 'showCommentByPostIdAndCommentId',
        description: 'Show comment by post id and comment id',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
    )]
    #[HttpHeaderAcceptLanguage]
    #[PathParameterPostId]
    #[PathParameterCommentId]
    #[OA\Response(
        response: 200,
        description: 'Comment',
        content: new OA\JsonContent(ref: CommentResource::class)
    )]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    #[HttpApiErrorResponse(response: 404, description: 'Not found')]
    #[HttpApiErrorResponse(response: 500, description: 'Server error')]
    public function show(BlogPost $post, Comment $comment): JsonResource
    {
        $comment->loadMissing('user');

        return new CommentResource($comment);
    }

    #[OA\Put(
        path: '/v1/posts/{post}/comments/{comment}',
        operationId: 'updateCommentById',
        description: 'Update comment by comment id',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
    )]
    #[HttpHeaderAcceptLanguage]
    #[PathParameterPostId]
    #[PathParameterCommentId]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: CommentRequest::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Comment was updated',
        content: new OA\JsonContent(ref: CommentResource::class),
    )]
    #[HttpValidationErrorResponse]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    #[HttpApiErrorResponse(response: 403, description: 'Forbidden')]
    #[HttpApiErrorResponse(response: 404, description: 'Not found')]
    #[HttpApiErrorResponse(response: 500, description: 'Server error')]
    public function update(BlogPost $post, Comment $comment, CommentRequest $request): JsonResource
    {
        $dto = new CommentDto(...$request->validated());
        $comment->loadMissing('user');
        $comment->content = $dto->content;
        $comment->save();

        return new CommentResource($comment);
    }

    #[OA\Delete(
        path: '/v1/posts/{post}/comments/{comment}',
        operationId: 'destroyCommentById',
        description: 'Destroy comment by comment id',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
    )]
    #[HttpHeaderAcceptLanguage]
    #[PathParameterPostId]
    #[PathParameterCommentId]
    #[OA\Response(
        response: 204,
        description: 'Comment was destroyed',
        content: new OA\JsonContent()
    )]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    #[HttpApiErrorResponse(response: 403, description: 'Forbidden')]
    #[HttpApiErrorResponse(response: 404, description: 'Not found')]
    #[HttpApiErrorResponse(response: 500, description: 'Server error')]
    public function destroy(BlogPost $post, Comment $comment): Response
    {
        $comment->delete();

        return response()->noContent();
    }
}
