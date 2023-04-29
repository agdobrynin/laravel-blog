<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Models\Image as ImageModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ImageResizerAvatar implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly ImageModel $model,
        public readonly int        $width
    )
    {
        if ($model->imageable_type !== User::class) {
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

    /**
     * Execute the job.
     */
    public function handle(FilesystemManager $storage): void
    {
        $imagePath = $this->model->fullOrigPath();
        $image = Image::make($imagePath);

        if ($this->width < max($image->width(), $image->height())) {
            $width = $image->width() < $image->height() ? $this->width : null;
            $height = $width ? null : $this->width;

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $x = $width ? 0 : ceil(($image->width() - $this->width) / 2);
            $y = $height ? 0 : ceil(($image->height() - $this->width) / 2);

            $image->crop($this->width, $this->width, $x, $y);

            $file = $this->model->thumbFile(width: $this->width);

            $storage->put($file, $image->stream()->getContents());
        }
    }
}
