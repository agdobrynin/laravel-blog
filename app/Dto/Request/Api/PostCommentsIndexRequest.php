<?php

declare(strict_types=1);

namespace App\Dto\Request\Api;

use App\Contracts\DtoFromRequest;
use Illuminate\Http\Request;

readonly class PostCommentsIndexRequest implements DtoFromRequest
{
    public function __construct(public int $perPage, public int $page)
    {
    }

    public static function fromRequest(Request $request): PostCommentsIndexRequest
    {
        return new self((int)$request->input('per_page', 15), (int)$request->input('page', 1));
    }
}
