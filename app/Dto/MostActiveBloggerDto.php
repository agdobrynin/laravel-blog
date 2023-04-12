<?php
declare(strict_types=1);

namespace App\Dto;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

readonly class MostActiveBloggerDto
{
    public Collection $bloggers;

    public function __construct(
        Collection  $bloggers,
        public ?int $lastMonth = null,
        public ?int $minCountPost = null,
    )
    {
        throw_if($bloggers->count() && !($bloggers->first() instanceof User), message: 'Bloggers must be a collection of '.User::class);

        $this->bloggers = $bloggers;
    }
}
