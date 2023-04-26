<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Models\BlogPost;
use App\Models\Image as ImageModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ImageResizerBlogPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const BLOG_POST_IMAGE_WITH = 950;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly ImageModel $model)
    {
        if ($model->imageable_type !== BlogPost::class) {
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
        $imagePath = $this->model->fullPath();
        $image = Image::make($imagePath);

        if ($image->width() > self::BLOG_POST_IMAGE_WITH) {
            $image->resize(self::BLOG_POST_IMAGE_WITH, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }
    }
}
