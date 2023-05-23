<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Models\User;

readonly class CommentDto
{
    public function __construct(public string $content, public ?User $user = null)
    {
    }
}
