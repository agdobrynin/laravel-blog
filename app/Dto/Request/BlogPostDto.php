<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Contracts\DtoFromRequest;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BlogPostDto implements DtoFromRequest
{
    public function __construct(
        public string        $title,
        public string        $content,
        public Collection    $tags,
        public User          $user,
        public bool          $deleteImage,
        public ?UploadedFile $uploadedFile,
    )
    {
        throw_if(!$this->tags->first() instanceof Tag, message: 'Parameter tags must be collection of ' . Tag::class);
    }

    public static function fromRequest(Request $request): BlogPostDto
    {
        return new static(
            $request->input('title'),
            $request->input('content'),
            Tag::whereIn('id', $request->input('tags'))->get(),
            $request->user(),
            (bool)$request->input('delete_image'),
            $request->file('thumb')
        );
    }
}
