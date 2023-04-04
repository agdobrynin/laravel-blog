<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoPostInDatabase(): void
    {
        $response = $this->get('/post');

        $response->assertOk();
        $response->assertSeeText('Posts not found');
    }

    public function testAddPostAndSeeItOnPageWithNoComments(): void
    {
        $post = BlogPost::create([
            'title' => 'Post title one',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);

        $response = $this->get('/post');

        $response->assertOk();
        $response->assertSeeText('Post title one');
        $response->assertSeeText('Lorem ipsum dolor sit amet');
        $response->assertSeeText('No comments yet.');

        $this->assertDatabaseHas('blog_posts', $post->toArray());
    }

    public function testAddPostAndSeeItOnPageWithFiveComments(): void
    {
        /** @var BlogPost $post */
        $post = BlogPost::factory()->create();
        $comments = Comment::factory(5)->make();
        $post->comments()->saveMany($comments);

        $response = $this->get('/post');

        $response->assertOk();
        $response->assertSeeText($post->title);

        $limitContent = Str::limit($post->content,  50);
        $response->assertSeeText($limitContent);
        $response->assertSeeText('Has comments');
        $response->assertSeeText('💬 5');
    }

    public function testStorePost(): void
    {
        $data = [
            'title' => 'Post new for store',
            'content' => 'Long content in new post'
        ];

        $response = $this->post('/post', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'New post was created');
    }

    public function testStorePostFailValidationRequired(): void
    {
        $data = [
            'title' => '',
            'content' => '',
        ];

        $response = $this->post('/post', $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'content' => 'The content field is required.',
        ]);
    }

    public function testStorePostFailValidationTooShort(): void
    {
        $data = [
            'title' => 't',
            'content' => 'c',
        ];

        $response = $this->post('/post', $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => 'The title field must be at least 5 characters.',
            'content' => 'The content field must be at least 10 characters.',
        ]);
    }

    public function testEditPostSuccess(): void
    {
        $post = new BlogPost();
        $post->title = 'The new title';
        $post->content = 'Long new content';
        $post->save();

        $this->assertDatabaseHas('blog_posts', $post->toArray());
        $url = sprintf('/post/%s', $post->id);

        $response = $this->put($url, ['title' => 'title updated', 'content' => 'Updated content']);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Post was updated');
        $this->assertDatabaseHas('blog_posts', ['title' => 'title updated']);
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
    }

    public function testDelete(): void
    {
        $post = BlogPost::create([
            'title' => 'title one',
            'content' => 'long content here',
        ]);

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $url = sprintf('/post/%s', $post->id);

        $response = $this->delete($url)
            ->assertStatus(302);

        $successMessage = sprintf('Post "%s" was deleted', $post->title);
        $response->assertSessionHas('success', $successMessage);
    }
}
