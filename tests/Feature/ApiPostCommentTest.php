<?php

namespace Tests\Feature;

use App\Enums\RolesEnum;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiPostCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider dataForbiddenRoute */
    public function testForbiddenRoute(string $method, string $route): void
    {
        $this->json($method, $route)->assertUnauthorized();
    }

    public function dataForbiddenRoute(): Generator
    {
        yield 'comments get' => ['get', '/api/v1/posts/1/comments'];
        yield 'comments post' => ['post', '/api/v1/posts/1/comments'];
        yield 'one comment get' => ['get', '/api/v1/posts/1/comments/1'];
        yield 'comment put' => ['put', '/api/v1/posts/1/comments/1'];
        yield 'comment delete' => ['delete', '/api/v1/posts/1/comments/1'];
    }

    public function testGetPostWithoutComments(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/posts/' . $post->id . '/comments');

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data');
    }

    public function testGetPostFiveComments(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();
        $post->commentsOn()->saveMany(Comment::factory(5)->make());

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/posts/' . $post->id . '/comments')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'createdAt',
                        'updatedAt',
                        'user',
                    ]
                ],
                'links',
                'meta'
            ])
            ->assertJsonCount(5, 'data');
    }

    public function testAddCommentSuccess(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();

        Sanctum::actingAs($user);

        $this->postJson(
            '/api/v1/posts/' . $post->id . '/comments',
            ['content' => 'First comment here']
        )
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'createdAt',
                    'updatedAt',
                    'user' => [
                        'id',
                        'name',
                        'avatar'
                    ],
                ]
            ]);
    }

    public function testAddCommentFailedValidation(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();

        Sanctum::actingAs($user);

        $this->postJson(
            '/api/v1/posts/' . $post->id . '/comments',
            ['content' => 'short']
        )
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The content field must be at least 10 characters.',
                'errors' => [
                    'content' => ['The content field must be at least 10 characters.'],
                ],
            ]);
    }

    public function testEditCommentSuccess(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs($user);

        $this->putJson(
            '/api/v1/posts/' . $post->id . '/comments/' . $comment->id,
            ['content' => 'Update comment here']
        )
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $comment->id,
                    'content' => 'Update comment here',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => null,
                    ]
                ]
            ]);
    }

    public function testEditCommentFailedNotOwner(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson(
            '/api/v1/posts/' . $post->id . '/comments/' . $comment->id,
            ['content' => 'Update comment here']
        )
            ->assertForbidden()
            ->assertJson([
                'message' => 'You are not owner this comment'
            ]);
    }

    public function testDeleteCommentSuccess(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs($user);

        $this->deleteJson('/api/v1/posts/' . $post->id . '/comments/' . $comment->id)
            ->assertNoContent();
    }

    public function testDeleteCommentFailedNotOwner(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson('/api/v1/posts/' . $post->id . '/comments/' . $comment->id)
            ->assertForbidden()
            ->assertJson(['message' => 'You are not owner this comment']);
    }

    public function testDeleteCommentSuccessByAdmin(): void
    {
        ['user' => $user, 'post' => $post] = $this->makeUserWithPost();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        /** @var User $admin */
        $admin = User::factory()->create();
        $role = Role::create('Admin', RolesEnum::ADMIN);
        $admin->roles()->attach($role);

        $requestHeaders = [
            'Authorization' => 'Bearer ' . $admin->createToken('test')->plainTextToken,
        ];

        $this->deleteJson(
            '/api/v1/posts/' . $post->id . '/comments/' . $comment->id,
            headers: $requestHeaders
        )
            ->assertNoContent();
    }

    protected function makeUserWithPost(): array
    {
        $user = User::factory()->hasBlogPosts()->create();
        /** @var BlogPost $post */
        $post = $user->blogPosts()->first();

        return ['user' => $user, 'post' => $post];
    }
}
