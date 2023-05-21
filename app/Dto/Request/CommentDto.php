<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Contracts\DtoFromRequest;
use App\Models\User;
use Illuminate\Http\Request;

readonly class CommentDto implements DtoFromRequest
{
    public function __construct(public string $content, public ?User $user)
    {
    }

    public static function fromRequest(Request $request): static
    {
        $user = $request->routeIs('api/*') ? $request->user('sanctum') : $request->user();
        $data = $request->validate([
            'content' => 'required|min:10',
        ]);

        return new static(...$data, user: $user);
    }
}
