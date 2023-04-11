<?php
declare(strict_types=1);

namespace App\Dto;

use App\Enums\OrderBlogPostEnum;

readonly class BlogPostFilterDto
{
    public function __construct(
        public OrderBlogPostEnum $order = OrderBlogPostEnum::LATEST_UPDATED
    )
    {
    }
}
