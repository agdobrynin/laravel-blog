<?php
declare(strict_types=1);

namespace App\View\Components\Comment;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListWithPagination extends Component
{
    public function __construct(readonly public LengthAwarePaginator $comments)
    {
    }

    public function render(): View
    {
        return view('components.comment.list-with-pagination');
    }
}
