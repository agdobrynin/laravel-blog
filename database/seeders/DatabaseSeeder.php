<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BlogPost as Posts;

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
    }
}
