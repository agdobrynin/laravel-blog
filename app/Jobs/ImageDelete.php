<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Enums\StoragePathEnum;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ImageDelete implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $imageableType,
        public readonly string $path,
    )
    {
        if (!\in_array($imageableType, [User::class, BlogPost::class])) {
            $message = trans(
                'Неизвестный тип изображения для удаления файлов :type',
                ['type' => $imageableType]
            );

            throw new \RuntimeException(message: $message);
        }

        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    public function uniqueId(): string
    {
        return $this->imageableType.'-'.$this->path;
    }

     public function handle(FilesystemManager $storage): void
    {
        $storage->delete($this->path);

        $directory = $this->imageableType === User::class
            ? StoragePathEnum::USER_AVATAR_THUMB->value
            : StoragePathEnum::POST_IMAGE_THUMB->value;

        $files = $storage->files($directory);
        $baseFileName = pathinfo($this->path, PATHINFO_FILENAME);

        foreach ($files as $file) {
            $fileName = pathinfo($file, PATHINFO_FILENAME);

            if (Str::startsWith($fileName, $baseFileName)) {
                $storage->delete($file);
            }
        }
    }
}
