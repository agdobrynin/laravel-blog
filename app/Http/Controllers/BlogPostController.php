<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::latest()->withCount('comments')->get();

        return view('post.list', ['posts' => $posts]);
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
        $post = BlogPost::create($request->validated());

        return redirect()
            ->route('post.show', ['post' => $post])
            ->with('success', trans('Новый пост создан успешно'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $post)
    {
        $post->loadMissing('comments');

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
