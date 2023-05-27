<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Info about authenticated user',
    required: ['id', 'name', 'email', 'createdAt', 'updatedAt'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', description: 'User name', type: 'string', example: 'Destiny Amy'),
        new OA\Property(property: 'email', description: 'Email', type: 'string', example: 'felix@example.net'),
        new OA\Property(property: 'createdAt', description: 'Date and time created comment as ISO format', type: 'string', example: '2023-04-29 19:00:58'),
        new OA\Property(property: 'updatedAt', description: 'Date and time updated comment as ISO format', type: 'string', example: '2023-04-29 19:00:58'),
        new OA\Property(property: 'avatar', description: 'Url to user avatar image', type: 'string', example: 'http://localhost//avatars/bvE.jpg', nullable: true),
    ],
)]
class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => (string)$this->created_at,
            'updatedAt' => (string)$this->updated_at,
            'avatar' => $this->image?->path ? $this->image->thumbUrl(256) : null
        ];
    }
}
