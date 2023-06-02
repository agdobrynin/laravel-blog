<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                required: ['id', 'content', 'createdAt', 'updatedAt'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer'),
                    new OA\Property(property: 'content', type: 'string'),
                    new OA\Property(property: 'createdAt', description: 'Date and time created comment as ISO format', type: 'string'),
                    new OA\Property(property: 'updatedAt', description: 'Date and time updated comment as ISO format', type: 'string'),
                    new OA\Property(property: 'user', ref: CommentUserResource::class),
                ],
            )
        ),
    ],
)]
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'createdAt' => (string)$this->created_at,
            'updatedAt' => (string)$this->updated_at,
            'user' => new CommentUserResource($this->whenLoaded('user')),
        ];
    }
}
