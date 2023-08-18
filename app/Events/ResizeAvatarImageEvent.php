<?php

namespace App\Events;

readonly class ResizeAvatarImageEvent
{
    public function __construct(
        public string $path,
        public int    $width,
        public ?int   $height = null,
    )
    {
    }
}
