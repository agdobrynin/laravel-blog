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
        if ($this->command->confirm('Refresh database ?')) {
            $this->command->call('mig:ref');
            $this->command->info('Database was refreshed');
        }

        $this->call([
            UserSeeder::class,
            BlogPostSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
