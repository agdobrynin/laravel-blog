<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->command->confirm('Refresh database ?', true)) {
            $this->command->call('mig:ref');
            $this->command->info('Database was refreshed');
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
