<?php

namespace App\Http\Controllers;

use App\Dto\BlogPostFilterDto;
use App\Dto\MostActiveBloggerDto;
use App\Enums\CacheTagsEnum;
use App\Enums\OrderBlogPostEnum;
use App\Factory\OrderBlogPostFactory;
use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;
use App\Models\Tag;
use App\Services\Contracts\MostActiveBloggersInterface;
use App\Services\Contracts\ReadNowObjectInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
    public function index(Request $request, MostActiveBloggersInterface $mostActiveBloggers)
    {
        $order = OrderBlogPostFactory::make($request->get('order', ''))
            ?: OrderBlogPostEnum::LATEST_UPDATED;
        $tagId = (int)$request->get('tag');

        $tags = Cache::tags(CacheTagsEnum::TAGS->value)->remember(
            Tag::class,
            env('BLOG_POST_TAGS_CACHE_TTL'),
            fn() => Tag::orderBy('name', 'asc')->get()
        );

        $filterDto = new BlogPostFilterDto($order, $tags->find($tagId));
        $posts = BlogPost::filter($filterDto);

        $takeMostActiveBloggers = env('MOST_ACTIVE_BLOGGER_TAKE_USERS', 5);
        $bloggers = Cache::tags([
            CacheTagsEnum::BLOG_INDEX->value,
            CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value
        ])->remember(
            MostActiveBloggersInterface::class . $takeMostActiveBloggers,
            env('MOST_ACTIVE_BLOGGER_CACHE_TTL', 1800)
            , fn() => $mostActiveBloggers->get($takeMostActiveBloggers)
        );

        $mostActiveBloggers = new MostActiveBloggerDto(
            bloggers: $bloggers,
            lastMonth: $mostActiveBloggers->getLastMonth(),
            minCountPost: $mostActiveBloggers->getMinCountPost(),
        );

        return view(
            'post.list',
            [
                'posts' => $posts->get(),
                'filterDto' => $filterDto,
                'mostActiveBloggers' => $mostActiveBloggers,
                'tags' => $tags,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        /** @var BlogPost $post */
        $post = BlogPost::create($data);

        return redirect()
            ->route('post.show', ['post' => $post])
            ->with('success', trans('Новый пост создан успешно'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $post, ReadNowObjectInterface $readNowObject)
    {
        $post->loadMissing(['comments.user', 'tags']);

        return view('post.show', [
            'post' => $post,
            'pageTitle' => Str::limit($post->title, 30),
            'readCount' => $readNowObject->readNowCount($post->id, session()->getId()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $post)
    {
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostRequest $request, BlogPost $post)
    {
        $post->update($request->validated());

        return redirect()
            ->route('post.show', ['post' => $post])
            ->with('success', trans('Пост обновлен'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $post)
    {
        $post->delete();
        $message = trans('Пост ":title" успешно удален', ['title' => $post->title]);

        return redirect()
            ->route('post.index')
            ->with('success', $message);
    }

    public function restore(BlogPost $post)
    {
        if ($post->restore()) {
            $message = trans('Пост ":title" успешно восстановлен', ['title' => $post->title]);

            return back()
                ->with('success', $message);
        }

        abort(500, 'Cant restore post');
    }
}
