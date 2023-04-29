<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\StoragePathEnum;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\FilesystemManager;
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

            foreach (StoragePathEnum::cases() as $case) {
                $storage->deleteDirectory($case->value);
                $this->command->info('Delete storage directory: '.$case->value);
            }
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
