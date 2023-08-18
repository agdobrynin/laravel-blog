<?php

namespace App\Events;

readonly class ResizeBlogPostImageEvent
{
    public function __construct(
        public string $path,
        public int    $width,
        public ?int   $height = null,
    )
    {
    }
}
