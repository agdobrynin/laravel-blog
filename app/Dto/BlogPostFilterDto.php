<?php
declare(strict_types=1);

namespace App\Dto;

use App\Enums\OrderBlogPostEnum;
use App\Models\Tag;
use App\Models\User;

readonly class BlogPostFilterDto
{
    public function __construct(
        public OrderBlogPostEnum $order = OrderBlogPostEnum::LATEST_UPDATED,
        public ?Tag $tag = null,
        public ?User $user = null,
    )
    {
    }
}
