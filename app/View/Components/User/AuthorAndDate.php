<?php

namespace App\View\Components\User;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthorAndDate extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public readonly ?\DateTimeInterface $createdAt = null,
        public readonly ?\DateTimeInterface $updatedAt = null,
        public readonly ?User $user = null,
        public readonly int $avatarSize = 48,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user.author-and-date');
    }
}
