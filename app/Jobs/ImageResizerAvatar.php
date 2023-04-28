<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Enums\StoragePathEnum;
use App\Models\Image as ImageModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageResizerAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly ImageModel $model,
        public readonly int $with
    )
    {
        if ($model->imageable_type !== User::class) {
            $message = trans(
                'Неизвестный тип изображения для ресайза :type',
                ['type' => $model->imageable_type]
            );

            throw new \RuntimeException(message: $message);
        }

        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    /**
     * Execute the job.
     */
    public function handle(FilesystemManager $storage): void
    {
        $imagePath = $this->model->fullOrigPath();
        $image = Image::make($imagePath);

        if ($this->with < max($image->width(), $image->height())) {
            $width = $image->width() < $image->height() ? $this->with : null;
            $height = $width ? null : $this->with;

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $x = $width ? 0 : ceil(($image->width() - $this->with) / 2);
            $y = $height ? 0 : ceil(($image->height() - $this->with) / 2);

            $image->crop($this->with, $this->with, $x, $y);

            throw new \LogicException('Not realized yet :(');
//            $storage->putFile(StoragePathEnum::USER_AVATAR_THUMB->value, )
//
//            $pathThumb = StoragePathEnum::USER_AVATAR_THUMB->value.DIRECTORY_SEPARATOR.$image->basename;
//            $image->save(Storage::path($pathThumb));
//            $this->model->path_thumb = $pathThumb;
//            $this->model->save();
        }
    }
}
