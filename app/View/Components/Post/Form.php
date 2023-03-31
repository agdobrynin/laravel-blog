<?php

namespace App\View\Components\Post;

use App\Models\BlogPost;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        readonly public string $route,
        readonly public string $actionTitle,
        readonly public ?BlogPost $post
    )
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.post.form');
    }
}
