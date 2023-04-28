<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Models\Image as ImageModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ImageResizerAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const AVATAR_SQUARE_WITH = 256;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly ImageModel $model)
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
    public function handle(): void
    {
        $imagePath = $this->model->fullOrigPath();
        $image = Image::make($imagePath);

        if (self::AVATAR_SQUARE_WITH < max($image->width(), $image->height())) {
            $width = $image->width() < $image->height() ? self::AVATAR_SQUARE_WITH : null;
            $height = $width ? null : self::AVATAR_SQUARE_WITH;

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $x = $width ? 0 : ceil(($image->width() - self::AVATAR_SQUARE_WITH) / 2);
            $y = $height ? 0 : ceil(($image->height() - self::AVATAR_SQUARE_WITH) / 2);

            $image->crop(self::AVATAR_SQUARE_WITH, self::AVATAR_SQUARE_WITH, $x, $y);
            $image->save();
        }
    }
}
