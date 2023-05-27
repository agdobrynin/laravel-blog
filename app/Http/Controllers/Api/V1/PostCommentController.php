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
use App\Swagger\CommentResourceCollection;
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
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
            new OA\PathParameter(name: 'post', description: 'Blog post Id', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\QueryParameter(ref: '#/components/parameters/QueryParameterPerPage'),
            new OA\QueryParameter(ref: '#/components/parameters/QueryParameterPage'),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 401),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 404),
            new OA\Response(ref: '#/components/responses/ResponseApiValidationError', response: 422),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 500),
            new OA\Response(response: 200, description: 'Comments list with pagination for blog post id',
                content: new OA\JsonContent(ref: CommentResourceCollection::class)
            ),
        ],
    )]
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
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentRequest::class)),
        tags: ['Comments'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
            new OA\PathParameter(name: 'post', description: 'Blog post Id', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 401),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 404),
            new OA\Response(ref: '#/components/responses/ResponseApiValidationError', response: 422),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 500),
            new OA\Response(response: 201, description: 'Comment was added',
                content: new OA\JsonContent(ref: CommentResource::class)
            ),
        ],
    )]
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
        $resource = new CommentResource($comment);
        $resource::$wrap = null;

        return $resource;
    }

    #[OA\Get(
        path: '/v1/posts/{post}/comments/{comment}',
        operationId: 'showCommentByPostIdAndCommentId',
        description: 'Show comment by post id and comment id',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
            new OA\PathParameter(name: 'post', description: 'Blog post Id', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\PathParameter(name: 'comment', description: 'Comment Id', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 401),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 404),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 500),
            new OA\Response(response: 200, description: 'Comment', content: new OA\JsonContent(ref: CommentResource::class)
            ),
        ],
    )]
    public function show(BlogPost $post, Comment $comment): JsonResource
    {
        $comment->loadMissing('user');
        $resource = new CommentResource($comment);
        $resource::$wrap = null;

        return $resource;
    }

    #[OA\Put(
        path: '/v1/posts/{post}/comments/{comment}',
        operationId: 'updateCommentById',
        description: 'Update comment by comment id',
        security: [['apiKeyBearer' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentRequest::class)),
        tags: ['Comments'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
            new OA\PathParameter(name: 'post', description: 'Blog post Id', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\PathParameter(name: 'comment', description: 'Comment Id', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 401),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 403),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 404),
            new OA\Response(ref: '#/components/responses/ResponseApiValidationError', response: 422),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 500),
            new OA\Response(response: 200, description: 'Comment was updated',
                content: new OA\JsonContent(ref: CommentResource::class)
            ),
        ],
    )]
    public function update(BlogPost $post, Comment $comment, CommentRequest $request): JsonResource
    {
        $dto = new CommentDto(...$request->validated());
        $comment->loadMissing('user');
        $comment->content = $dto->content;
        $comment->save();

        $resource = new CommentResource($comment);
        $resource::$wrap = null;

        return $resource;
    }

    #[OA\Delete(
        path: '/v1/posts/{post}/comments/{comment}',
        operationId: 'destroyCommentById',
        description: 'Destroy comment by comment id',
        security: [['apiKeyBearer' => []]],
        tags: ['Comments'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Accept-Language'),
            new OA\PathParameter(name: 'post', description: 'Blog post Id', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\PathParameter(name: 'comment', description: 'Comment Id', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 401),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 403),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 404),
            new OA\Response(ref: '#/components/responses/ResponseApiError', response: 500),
            new OA\Response(response: 204, description: 'Comment was destroyed', content: new OA\JsonContent()),
        ],
    )]
    public function destroy(BlogPost $post, Comment $comment): Response
    {
        $comment->delete();

        return response()->noContent();
    }
}
