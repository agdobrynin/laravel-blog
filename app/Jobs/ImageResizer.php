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
            case BlogPost::class: {
                $image->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                break;
            }
            default:
                $image->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();

                });
                $image->crop(50, 50);
        }

        $image->save($imagePath);
    }
}
