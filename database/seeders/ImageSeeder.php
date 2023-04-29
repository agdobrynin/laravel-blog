<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StoragePathEnum;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Artisan;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(FilesystemManager $storage): void
    {
        Artisan::call('storage:link');

        if ($this->command->confirm('Add avatar image for some users?', true)) {
            $avatars = glob(__DIR__ . '/images/avatars/*.*');

            User::all()->random(count($avatars))->unique()->each(function (User $user, int $index) use ($avatars, $storage) {
                $path = $storage
                    ->putFile(StoragePathEnum::USER_AVATAR->value, $avatars[$index]);
                $user->image()->save(new Image(['path' => $path]));
                $this->command->info('Add avatar for user ' . $user->getEmailForVerification());
            });
        }

        if ($this->command->confirm('Add image for some Blog Posts?', true)) {
            $thumbs = glob(__DIR__ . '/images/thumbs/*.*');

            BlogPost::all()->random(count($thumbs))->unique()->each(function (BlogPost $post, int $index) use ($thumbs, $storage) {
                $path = $storage
                    ->putFile(StoragePathEnum::POST_IMAGE->value, $thumbs[$index]);
                $post->image()->save(new Image(['path' => $path]));
                $this->command->info('Add image for post with title ' . $post->title);
            });
        }
    }
}
