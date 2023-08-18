<?php

namespace App\Listeners;

use App\Enums\QueueNamesEnum;
use App\Events\ResizeAvatarImageEvent;
use App\Services\Contracts\AvatarImageStorageInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Intervention\Image\Facades\Image;

class ResizeAvatarImageSubscriber implements ShouldQueue
{
    public readonly string $queue;

    public function __construct(protected AvatarImageStorageInterface $storage)
    {
        $this->queue = QueueNamesEnum::LOW->value;
    }

    public function handle(ResizeAvatarImageEvent $event): void
    {
        $imageBin = $this->storage->fileSystem()->get($event->path);
        $image = Image::make($imageBin);

        if ($event->width < max($image->width(), $image->height())) {
            $width = $image->width() < $image->height() ? $event->width : null;
            $height = $width ? null : $event->width;

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $x = $width ? 0 : ceil(($image->width() - $event->width) / 2);
            $y = $height ? 0 : ceil(($image->height() - $event->width) / 2);

            $image->crop($event->width, $event->width, $x, $y);

            $file = $this->storage->thumbFilePath($event->path, $event->width);

            $this->storage->fileSystem()->put($file, $image->stream()->getContents());
        }
    }
}
