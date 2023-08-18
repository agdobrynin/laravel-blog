<?php

namespace App\Http\Controllers;

use App\Dto\BlogPostFilterDto;
use App\Dto\Request\BlogPostDto;
use App\Enums\OrderBlogPostEnum;
use App\Events\BlogPostAdded;
use App\Factory\OrderBlogPostFactory;
use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use App\Services\Contracts\BlogPostImageStorageInterface;
use App\Services\Contracts\ReadNowObjectInterface;
use App\Services\Contracts\TagsDictionaryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['index', 'show']);
        $this->authorizeResource(BlogPost::class, 'post');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TagsDictionaryInterface $tagsDictionary): View
    {
        $order = OrderBlogPostFactory::make($request->get('order', ''))
            ?: OrderBlogPostEnum::LATEST_UPDATED;
        $tagId = (int)$request->get('tag');

        $tags = $tagsDictionary->tags();
        $user = User::find($request->get('user'));

        $filterDto = new BlogPostFilterDto($order, $tags->find($tagId), $user);
        $posts = BlogPost::filter($filterDto)
            ->paginate(env('BLOG_POSTS_PAGINATE_SIZE', 12))
            ->onEachSide(1)
            ->withQueryString();

        return view(
            'post.list',
            [
                'posts' => $posts,
                'filterDto' => $filterDto,
                'tags' => $tags,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        BlogPostRequest               $request,
        BlogPostImageStorageInterface $storage,
    ): RedirectResponse
    {
        $dto = new BlogPostDto(...$request->validated(), user: $request->user());

        $post = new BlogPost();
        $post->title = $dto->title;
        $post->content = $dto->content;
        $post->user()->associate($dto->user);
        $post->save();

        if ($file = $dto->uploadedFile) {
            $path = $storage->putFile($file);
            $image = new Image(['path' => $path]);
            $post->image()->save($image);
        }

        $post->tags()->sync($dto->tags);

        event(new BlogPostAdded($post));

        return redirect()
            ->route('posts.show', ['post' => $post])
            ->with('success', trans('Новый пост создан успешно'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, BlogPost $post, ReadNowObjectInterface $readNowObject): View
    {
        $comments = $post->commentsOn()->with(['user.image', 'tags'])
            ->withCount('tags')
            ->paginate(env('COMMENTS_PAGINATE_SIZE', 20))
            ->onEachSide(1)
            ->withQueryString();

        $post->loadMissing(['user.image', 'tags', 'image']);

        return view('post.show', [
            'post' => $post,
            'comments' => $comments,
            'pageTitle' => Str::limit($post->title, 30),
            'readCount' => $readNowObject->readNowCount($post, session()->getId()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, BlogPost $post): View
    {
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        string                        $locale,
        BlogPost                      $post,
        BlogPostRequest               $request,
        BlogPostImageStorageInterface $storage
    ): RedirectResponse
    {
        $dto = new BlogPostDto(...$request->validated(), user: $request->user());
        $post->title = $dto->title;
        $post->content = $dto->content;
        $post->save();
        $post->tags()->sync($dto->tags);

        if ($dto->deleteImage && $post->image) {
            $post->image->delete();
        }

        if ($file = $dto->uploadedFile) {
            $path = $storage->putFile($file);

            if ($post->image) {
                $post->image->delete();
            }

            $post->image()->save(new Image(['path' => $path]));
        }

        return redirect()
            ->route('posts.show', ['post' => $post])
            ->with('success', trans('Пост обновлен'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, BlogPost $post): RedirectResponse
    {
        $post->delete();
        $message = trans('Пост ":title" успешно удален', ['title' => $post->title]);

        return redirect()
            ->route('posts.index')
            ->with('success', $message);
    }

    public function restore(string $locale, BlogPost $post): RedirectResponse
    {
        if ($post->restore()) {
            $message = trans('Пост ":title" успешно восстановлен', ['title' => $post->title]);

            return back()
                ->with('success', $message);
        }

        abort(500, 'Cant restore post');
    }
}
