<?php

namespace App\Listeners;

use App\Enums\QueueNamesEnum;
use App\Events\ResizeBlogPostImageEvent;
use App\Services\Contracts\BlogPostImageStorageInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Intervention\Image\Facades\Image;

class ResizeBlogPostImageSubscriber implements ShouldQueue
{
    public readonly string $queue;

    public function __construct(protected BlogPostImageStorageInterface $storage)
    {
        $this->queue = QueueNamesEnum::LOW->value;
    }

    public function handle(ResizeBlogPostImageEvent $event): void
    {
        $imageBin = $this->storage->fileSystem()->get($event->path);
        $image = Image::make($imageBin);

        if ($image->width() > $event->width) {
            $image->resize($event->width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $file = $this->storage->thumbFilePath($event->path, $event->width);

            $this->storage->fileSystem()->put($file, $image->stream()->getContents());
        }
    }
}
