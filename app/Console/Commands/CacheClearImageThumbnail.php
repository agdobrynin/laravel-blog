<?php

namespace App\Console\Commands;

use App\Services\Contracts\AvatarImageStorageInterface;
use App\Services\Contracts\BlogPostImageStorageInterface;
use Illuminate\Console\Command;

class CacheClearImageThumbnail extends Command
{
    protected const AVATAR = 'avatar';
    protected const POST = 'post';

    protected $signature = 'cache:clear-image-thumbnail {--' . self::AVATAR . ' : Clear thumbnails for user avatars} {--' . self::POST . ' : Clear thumbnails for blog posts}';

    protected $description = 'Clear thumbnails for images (avatars, blog post)';

    public function handle(
        AvatarImageStorageInterface   $storageAvatar,
        BlogPostImageStorageInterface $storageBlogPost
    ): void
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

        foreach ($choices as $choice) {
            if ($choice === self::AVATAR) {
                $storageAvatar->thumbClear(null);
                $this->info('Clear thumbnails for avatar images');
            } elseif ($choice === self::POST) {
                $storageBlogPost->thumbClear(null);
                $this->info('Clear thumbnails for blog post images');
            }
        }
    }
}
