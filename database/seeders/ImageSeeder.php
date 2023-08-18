<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use App\Services\Contracts\AvatarImageStorageInterface;
use App\Services\Contracts\BlogPostImageStorageInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('storage:link');
        $storageAvatar = App::make(AvatarImageStorageInterface::class);
        $storagePost = App::make(BlogPostImageStorageInterface::class);

        if ($this->command->confirm('Add avatar image for some users?', true)) {
            $avatars = glob(__DIR__ . '/images/avatars/*.*');

            User::all()->random(count($avatars))->unique()->each(function (User $user, int $index) use ($avatars, $storageAvatar) {
                $path = $storageAvatar->putFile($avatars[$index]);

                $user->image()->save(new Image(['path' => $path]));
                $this->command->info('Add avatar for user ' . $user->getEmailForVerification());
            });
        }

        if ($this->command->confirm('Add image for some Blog Posts?', true)) {
            $thumbs = glob(__DIR__ . '/images/thumbs/*.*');

            BlogPost::all()->random(count($thumbs))->unique()->each(function (BlogPost $post, int $index) use ($thumbs, $storagePost) {
                $path = $storagePost->putFile($thumbs[$index]);

                $post->image()->save(new Image(['path' => $path]));
                $this->command->info('Add image for post with title ' . $post->title);
            });
        }
    }
}
