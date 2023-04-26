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
            case BlogPost::class:
            {
                if ($image->width() > 950) {
                    $image->resize(950, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save();
                }

                break;
            }

            case User::class:
            {
                if ($image->width() > 256) {
                    $image->resize(256, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop(256, 256)->save();
                }
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
