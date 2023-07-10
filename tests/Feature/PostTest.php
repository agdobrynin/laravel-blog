<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoPostInDatabase(): void
    {
        $this->get('/ru/posts')
            ->assertOk()
            ->assertSeeText(trans('Записей в блоге нет'));
    }

    public function testAddPostAndSeeItOnPageWithNoComments(): void
    {
        $post = BlogPost::factory([
            'title' => 'Post title one',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])->for(User::factory()->create())
            ->create();

        $this->get('/ru/posts')
            ->assertOk()
            ->assertSeeText('Post title one')
            ->assertSeeText('Lorem ipsum dolor sit amet')
            ->assertSeeText('💬 0');

        $this->assertDatabaseHas('blog_posts', $post->toArray());
    }

    public function testAddPostAndSeeItOnPageWithFiveComments(): void
    {
        $post = BlogPost::factory()
            ->for(User::factory()->create())
            ->has(Comment::factory(5), 'commentsOn')
            ->create();

        $this->get('/ru/posts')
            ->assertOk()
            ->assertSeeText($post->title)
            ->assertSeeText(Str::limit($post->content,  50))
            ->assertSeeText('💬 5');
    }

    public function testStorePostWithVerifiedUser(): void
    {
        $tag = Tag::create(['name' => 'Super tag']);

        $data = [
            'title' => 'Post new for store',
            'content' => 'Long content in new post',
            'tags' => [$tag->id],
        ];

        $this->actingAs(User::factory()->create())
            ->post('/ru/posts', $data)
            ->assertStatus(302)
            ->assertSessionHas('success', trans('Новый пост создан успешно'));
    }

    /** @dataProvider dataStore */
    public function testStoreValidation(array $data, array $sessionErrors): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/ru/posts', $data)
            ->assertRedirect()
            ->assertSessionHasErrors($sessionErrors);
    }

    public function dataStore(): Generator
    {
        yield 'empty keys data' => [[], [
            'title' => 'Поле наименование обязательно.',
            'content' => 'Поле контент обязательно.']];

        yield 'empty value data' => [['title' => '', 'content' => ''], [
            'title' => 'Поле наименование обязательно.',
            'content' => 'Поле контент обязательно.']];

        yield 'title is too short' => [['title' => 'a', 'content' => ''], [
            'title' => 'Количество символов в поле наименование должно быть не меньше 5.',
            'content' => 'Поле контент обязательно.']];

        yield 'title and description is too short' => [['title' => 'a', 'content' => 'a'], [
            'title' => 'Количество символов в поле наименование должно быть не меньше 5.',
            'content' => 'Количество символов в поле контент должно быть не меньше 10.']];
    }

    public function testEditPostSuccess(): void
    {
        $user = User::factory()->create();
        /** @var BlogPost $post */
        $post = BlogPost::factory(['title' => 'The new title', 'content' => 'Long new content'])
            ->for($user)
            ->create();
        $post->tags()->save(Tag::factory()->create());

        $this->assertDatabaseHas('blog_posts', $post->toArray());
        $url = sprintf('/ru/posts/%s', $post->id);
        $tagIds = $post->tags()->get()->pluck('id')->toArray();

        $this->actingAs($user)
            ->put($url, ['title' => 'title updated', 'content' => 'Updated content', 'tags' => $tagIds])
            ->assertStatus(302)
            ->assertSessionHas('success', trans('Пост обновлен'));

        $this->assertDatabaseHas('blog_posts', ['title' => 'title updated']);
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
    }

    public function testDelete(): void
    {
        $user = User::factory()->create();
        BlogPost::unsetEventDispatcher();

        $post = BlogPost::factory(['title' => 'title one', 'content' => 'long content here'])
            ->for($user)
            ->create();

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
