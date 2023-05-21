<?php

declare(strict_types=1);

namespace App\Dto\Request\Api;

use App\Contracts\DtoFromRequest;
use Illuminate\Http\Request;

readonly class PostCommentsIndexRequestDto implements DtoFromRequest
{
    public function __construct(public int $perPage)
    {
    }

    public static function fromRequest(Request $request): static
    {
        return new static((int)$request->input('perPage', 15));
    }
}
