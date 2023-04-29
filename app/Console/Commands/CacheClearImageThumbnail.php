<?php

namespace App\Console\Commands;

use App\Enums\StoragePathEnum;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class CacheClearImageThumbnail extends Command
{
    protected const AVATAR = 'avatar';
    protected const POST = 'post';

    protected $signature = 'cache:clear-image-thumbnail {--' . self::AVATAR . ' : Clear thumbnails for user avatars} {--' . self::POST . ' : Clear thumbnails for blog posts}';

    protected $description = 'Clear thumbnails for images (avatars, blog post)';

    public function handle(FilesystemManager $storage): void
    {
        $choices[] = $this->option(self::AVATAR) ? self::AVATAR : null;
        $choices[] = $this->option(self::POST) ? self::POST : null;
        $choices = array_filter($choices);

        if (!$choices) {
            $choices = $this->choice(
                'Choose thumbnails for clear',
                [
                    self::AVATAR => 'Users avatar thumbnails',
                    self::POST => 'Image blog posts'
                ],
                multiple: true
            );
        }

        $directories = array_reduce($choices, function ($acc, $choice) {
            if ($choice === self::AVATAR) {
                $acc[] = StoragePathEnum::USER_AVATAR_THUMB->value;
            } elseif ($choice === self::POST) {
                $acc[] = StoragePathEnum::POST_IMAGE_THUMB->value;
            }

            return $acc;
        }, []);

        foreach ($directories as $directory) {
            $storage->deleteDirectory($directory);
            $this->info('Delete directory with thumbnails: ' . $directory);
        }
    }
}
