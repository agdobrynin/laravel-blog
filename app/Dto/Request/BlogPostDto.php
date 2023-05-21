<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Contracts\DtoFromRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BlogPostDto implements DtoFromRequest
{
    public function __construct(
        public string        $title,
        public string        $content,
        /**
         * @var array<int> Ids of from Tag model.
         */
        public array         $tags,
        public User          $user,
        public ?bool         $deleteImage = null,
        public ?UploadedFile $uploadedFile = null,
    )
    {
    }

    public static function fromRequest(Request $request): static
    {
        $data = $request->validate([
            'title' => 'required|min:5|max:100',
            'content' => 'required|min:10',
            'tags' => 'required|array|min:1|exists:tags,id',
            'uploadedFile' => 'image|mimes:jpg,jpeg,png,svg,gif|max:3500|dimensions:min_width=200,min_height=100,max_width=4000,max_height=4000',
        ]);
        return new static(
            ...$data,
            user: $request->user(),
            deleteImage: (bool)$request->input('deleteImage'),
        );
    }
}
