<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiPostCommentTest extends TestCase
{
    use RefreshDatabase;

    public function testForbiddenAllRoutes(): void
    {
        $response = $this->json('GET', '/api/v1/posts/1/comments');
        $response->assertUnauthorized();

        $response = $this->json('POST', '/api/v1/posts/1/comments');
        $response->assertUnauthorized();

        $response = $this->json('GET', '/api/v1/posts/1/comments/1');
        $response->assertUnauthorized();

        $response = $this->json('PUT', '/api/v1/posts/1/comments/1');
        $response->assertUnauthorized();

        $response = $this->json('DELETE', '/api/v1/posts/1/comments/1');
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

        $response = $this->json('GET', '/api/v1/posts/' . $post->id . '/comments');

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

        $response = $this->json('GET', '/api/v1/posts/' . $post->id . '/comments');

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
}
