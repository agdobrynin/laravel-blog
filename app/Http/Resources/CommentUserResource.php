<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['id', 'name'],
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', description: 'User name', type: 'string'),
        new OA\Property(property: 'avatar', description: 'Url to user avatar image', type: 'string', nullable: true),
    ],
)]
class CommentUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->image?->path ? $this->image->thumbUrl(128) : null
        ];
    }
}
