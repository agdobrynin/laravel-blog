<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if (!$users->count()) {
            $this->command->info('No users - no posts');

            return;
        }

        $postCount = (int)max($this->command->ask('How many post in blog do you like ?', 30), 0);

        if (!$postCount) {
            $this->command->info('You choose no posts :(');

            return;
        }

        BlogPost::factory($postCount)->make()->each(static function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
