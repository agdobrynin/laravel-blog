<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserWithPostAndCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCount = max($this->command->ask('How many users do you like?', 10), 1);
        $postMaxCount = $this->command->choice(
            'How max post do you like for each user',
            [1, 10, 20, 30, 50, 100],
        );
        $commentMaxCount = max($this->command->ask('How max comment do you like for each post', 20), 1);

        $users = User::factory($userCount)
            ->has(
                BlogPost::factory()->count(rand(1, $postMaxCount))
            )
            ->create();

        // comments for posts
        BlogPost::all()->each(static function (BlogPost $post) use ($users, $commentMaxCount) {
            $post->comments()
                ->saveMany(
                    Comment::factory(rand(0, $commentMaxCount))
                        ->state(
                            new Sequence(
                                fn(Sequence $sequence) => ['user_id' => rand(0, 1) ? null : $users->random()->id]
                            )
                        )
                        ->make()
                );
        });
    }
}
