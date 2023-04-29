<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Models\BlogPost;
use App\Models\Image as ImageModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ImageResizerBlogPost implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly ImageModel $model,
        public readonly int        $width
    )
    {
        if ($model->imageable_type !== BlogPost::class) {
            $message = trans(
                'Неизвестный тип изображения для изменения размера :type',
                ['type' => $model->imageable_type]
            );

            throw new \RuntimeException(message: $message);
        }

        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    public function uniqueId(): string
    {
        return $this->model->id.'-'.$this->width;
    }

    public function handle(FilesystemManager $storage): void
    {
        $imagePath = $this->model->fullOrigPath();
        $image = Image::make($imagePath);

        if ($image->width() > $this->width) {
            $image->resize($this->width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $file = $this->model->thumbFile(width: $this->width);

            $storage->put($file, $image->stream()->getContents());
        }
    }
}
