<?php

namespace Database\Seeders;

use App\Enums\CacheTagsEnum;
use App\Models\BlogPost;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

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

        $tags = Tag::all();

        Cache::tags(CacheTagsEnum::READ_NOW_OBJECT->value)->flush();

        BlogPost::factory($postCount)->make()->each(static function (BlogPost $post) use ($users, $tags) {
            $post->user_id = $users->random()->id;
            $post->save();
            $randTags = $tags->random(rand(0, $tags->count()));
            $post->tags()->attach($randTags);
        });
    }
}
