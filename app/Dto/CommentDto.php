<?php
declare(strict_types=1);

namespace App\Dto;

use App\Models\User;
use Illuminate\Http\Request;

readonly class CommentDto
{
    public string $content;
    public ?User $user;

    public function __construct(Request $request)
    {
        $this->content = $request->input('content');
        $this->user = $request->user();
    }
}
