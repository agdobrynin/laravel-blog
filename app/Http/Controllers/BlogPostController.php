<?php

namespace App\Http\Controllers;

use App\Dto\BlogPostFilterDto;
use App\Dto\MostActiveBloggerDto;
use App\Enums\OrderBlogPostEnum;
use App\Factory\OrderBlogPostFactory;
use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;
use App\Models\Role;
use App\Models\User;
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
    public function index(Request $request)
    {
        $order = OrderBlogPostFactory::make($request->get('order', '')) ?: OrderBlogPostEnum::LATEST_UPDATED;

        $filterDto = new BlogPostFilterDto($order);
        $posts = BlogPost::filter($filterDto);

        // For most active bloggers
        $lastMonth = env('MOST_ACTIVE_BLOGGER_LAST_MONTH');
        $minCountPost = env('MOST_ACTIVE_BLOGGER_MIN_POSTS', 5);

        $bloggers = User::withMostBlogPostLastMonth($lastMonth, $minCountPost)->get();

        $mostActiveBloggers = new MostActiveBloggerDto(
            bloggers: $bloggers,
            lastMonth: $lastMonth,
            minCountPost: $minCountPost,
        );

        return view(
            'post.list',
            [
                'posts' => $posts->get(),
                'filterDto' => $filterDto,
                'mostActiveBloggers' => $mostActiveBloggers,
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
    public function show(BlogPost $post)
    {
        $post->loadMissing(['comments.user', 'user']);

        return view('post.show', [
            'post' => $post,
            'pageTitle' => Str::limit($post->title, 30),
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
}
