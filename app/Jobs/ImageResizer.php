<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Models\BlogPost;
use App\Models\Image as ImageModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ImageResizer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const BLOG_POST_IMAGE_WITH = 950;
    protected const AVATAR_SQUARE_WITH = 256;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly ImageModel $model)
    {
        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $imagePath = $this->model->fullPath();
        $image = Image::make($imagePath);


        switch ($this->model->imageable_type) {
            case BlogPost::class :
            {
                if ($image->width() > self::BLOG_POST_IMAGE_WITH) {
                    $image->resize(self::BLOG_POST_IMAGE_WITH, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save();
                }

                break;
            }

            case User::class :
            {
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

                break;
            }

            default:
                throw new \LogicException(
                    trans(
                        'Неизвестный тип изображения для ресайза :type',
                        ['type' => $this->model->imageable_type]
                    )
                );
        }
    }
}
