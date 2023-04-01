<?php

namespace App\View\Components\Post;

use App\Models\BlogPost as Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class DisplayShort extends Component
{
    public function __construct(public readonly Post $post)
    {
    }

    public function shortContent(): string
    {
        return Str::limit($this->post->content,  50);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.post.display-short');
    }
}
