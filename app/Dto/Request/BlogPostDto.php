<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Models\User;
use Illuminate\Http\UploadedFile;

readonly class BlogPostDto
{
    public ?bool $deleteImage;

    public function __construct(
        public string        $title,
        public string        $content,
        /**
         * @var array<int> Ids of from Tag model.
         */
        public array         $tags,
        public User          $user,
        ?string              $deleteImage = null,
        public ?UploadedFile $uploadedFile = null,
    )
    {
        $this->deleteImage = (bool)$deleteImage;
    }
}
