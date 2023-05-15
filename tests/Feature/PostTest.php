<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoPostInDatabase(): void
    {
        $response = $this->get('/ru/posts');

        $response->assertOk();
        $response->assertSeeText(trans('Записей в блоге нет'));
    }

    public function testAddPostAndSeeItOnPageWithNoComments(): void
    {
        $post = BlogPost::make([
            'title' => 'Post title one',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);
        $post->user_id = User::factory()->create()->id;
        $post->save();

        $response = $this->get('/ru/posts');

        $response->assertOk();
        $response->assertSeeText('Post title one');
        $response->assertSeeText('Lorem ipsum dolor sit amet');
        $response->assertSeeText('💬 0');

        $this->assertDatabaseHas('blog_posts', $post->toArray());
    }

    public function testAddPostAndSeeItOnPageWithFiveComments(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory()->make();
        $post->user_id = $user->id;
        $post->save();
        $comments = Comment::factory(5)->make();
        $post->commentsOn()->saveMany($comments);

        $response = $this->get('/ru/posts');

        $response->assertOk();
        $response->assertSeeText($post->title);

        $limitContent = Str::limit($post->content,  50);
        $response->assertSeeText($limitContent);
        $response->assertSeeText('💬 5');
    }

    public function testStorePostWithVerifiedUser(): void
    {
        $tag = Tag::create(['name' => 'Super tag']);

        $data = [
            'title' => 'Post new for store',
            'content' => 'Long content in new post',
            'tags' => [$tag->id],
        ];
        // User with verified email
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/ru/posts', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', trans('Новый пост создан успешно'));
    }

    public function testStorePostFailValidationRequired(): void
    {
        $data = [
            'title' => '',
            'content' => '',
        ];
        // User with verified email
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/ru/posts', $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => 'Поле наименование обязательно.',
            'content' => 'Поле контент обязательно.',
        ]);
    }

    public function testStorePostFailValidationTooShort(): void
    {
        $data = [
            'title' => 't',
            'content' => 'c',
        ];
        // User with verified email
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/ru/posts', $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'title' => 'Количество символов в поле наименование должно быть не меньше 5.',
            'content' => 'Количество символов в поле контент должно быть не меньше 10.',
        ]);
    }

    public function testEditPostSuccess(): void
    {
        // User with verified email
        $user = User::factory()->create();
        $tag = Tag::create(['name' => 'Yap!']);

        $post = new BlogPost();
        $post->title = 'The new title';
        $post->content = 'Long new content';
        $post->user_id = $user->id;
        $post->save();
        $post->tags()->save($tag);

        $this->assertDatabaseHas('blog_posts', $post->toArray());
        $url = sprintf('/ru/posts/%s', $post->id);

        $response = $this->actingAs($user)
            ->put($url, ['title' => 'title updated', 'content' => 'Updated content', 'tags' => [$tag->id]]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', trans('Пост обновлен'));
        $this->assertDatabaseHas('blog_posts', ['title' => 'title updated']);
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
    }

    public function testDelete(): void
    {
        // User with verified email
        $user = User::factory()->create();

        BlogPost::unsetEventDispatcher();

        $post = BlogPost::make([
            'title' => 'title one',
            'content' => 'long content here',
        ]);

        $post->user_id = $user->id;
        $post->save();

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $url = sprintf('/ru/posts/%s', $post->id);

        $response = $this->actingAs($user)
            ->delete($url)
            ->assertStatus(302);

        $successMessage = trans('Пост ":title" успешно удален', ['title' => $post->title]);
        $response->assertSessionHas('success', $successMessage);
        $this->assertSoftDeleted('blog_posts', ['id' => $post->id]);
    }
}
