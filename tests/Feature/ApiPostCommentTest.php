<?php

namespace Tests\Feature;

use App\Enums\RolesEnum;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiPostCommentTest extends TestCase
{
    use RefreshDatabase;

    public function testForbiddenAllRoutes(): void
    {
        $response = $this->getJson('/api/v1/posts/1/comments');
        $response->assertUnauthorized();

        $response = $this->postJson('/api/v1/posts/1/comments');
        $response->assertUnauthorized();

        $response = $this->getJson('/api/v1/posts/1/comments/1');
        $response->assertUnauthorized();

        $response = $this->putJson('/api/v1/posts/1/comments/1');
        $response->assertUnauthorized();

        $response = $this->deleteJson('/api/v1/posts/1/comments/1');
        $response->assertUnauthorized();
    }

    public function testGetPostWithoutComments(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/posts/' . $post->id . '/comments');

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data');
    }

    public function testGetPostFiveComments(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();
        $post->commentsOn()->saveMany(Comment::factory(5)->make());

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/posts/' . $post->id . '/comments');

        $response->assertOk()
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
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();

        Sanctum::actingAs($user);

        $response = $this->postJson(
            '/api/v1/posts/' . $post->id . '/comments',
            ['content' => 'First comment here']
        );

        $response->assertCreated()
            ->assertJsonStructure([
                'id',
                'content',
                'createdAt',
                'updatedAt',
                'user' => [
                    'id',
                    'name',
                    'avatar'
                ],
            ]);
    }

    public function testAddCommentFailedValidation(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();

        Sanctum::actingAs($user);

        $response = $this->postJson(
            '/api/v1/posts/' . $post->id . '/comments',
            ['content' => 'short']
        );

        $response->assertUnprocessable()
            ->assertJson([
                'message' => 'The content field must be at least 10 characters.',
                'errors' => [
                    'content' => ['The content field must be at least 10 characters.'],
                ],
            ]);
    }

    public function testEditCommentSuccess(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs($user);

        $response = $this->putJson(
            '/api/v1/posts/' . $post->id . '/comments/' . $comment->id,
            ['content' => 'Update comment here']
        );

        $response->assertOk()
            ->assertJson([
                'id' => $comment->id,
                'content' => 'Update comment here',
            ]);
    }

    public function testEditCommentFailedNotOwner(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->putJson(
            '/api/v1/posts/' . $post->id . '/comments/' . $comment->id,
            ['content' => 'Update comment here']
        );

        $response->assertForbidden()
            ->assertJson([
                'message' => 'You are not owner this comment'
            ]);
    }

    public function testDeleteCommentSuccess(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/posts/' . $post->id . '/comments/' . $comment->id);

        $response->assertNoContent();
    }

    public function testDeleteCommentFailedNotOwner(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->deleteJson('/api/v1/posts/' . $post->id . '/comments/' . $comment->id);

        $response->assertForbidden()
            ->assertJson(['message' => 'You are not owner this comment']);
    }

    public function testDeleteCommentSuccessByAdmin(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user()->associate($user);
        $post->save();
        $comment = Comment::factory()->make(['user_id' => $user->id]);
        $post->commentsOn()->save($comment);

        /** @var User $admin */
        $admin = User::factory()->create();
        $role = Role::create('Admin', RolesEnum::ADMIN);
        $admin->roles()->attach($role);

        $requestHeaders = [
            'Authorization' => 'Bearer ' . $admin->createToken('test')->plainTextToken,
        ];

        $response = $this->deleteJson(
            '/api/v1/posts/' . $post->id . '/comments/' . $comment->id,
            headers: $requestHeaders
        );

        $response->assertNoContent();
    }
}
