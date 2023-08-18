<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\Contracts\AvatarImageStorageInterface;
use App\Services\Contracts\BlogPostImageStorageInterface;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(FilesystemManager $storage): void
    {
        if ($this->command->confirm('Refresh database ?', true)) {
            $this->command->call('mig:ref');
            $this->command->info('Database was refreshed');

            /** @var AvatarImageStorageInterface $storageAvatar */
            $storageAvatar = App::make(AvatarImageStorageInterface::class);
            /** @var BlogPostImageStorageInterface $storagePost */
            $storagePost = App::make(BlogPostImageStorageInterface::class);

            $storageAvatar->fileSystem()->deleteDirectory('');
            $storagePost->fileSystem()->deleteDirectory('');
        }

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TagSeeder::class,
            BlogPostSeeder::class,
            CommentSeeder::class,
            ImageSeeder::class,
        ]);

        Artisan::call('cache:clear');
    }
}
