<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = BlogPost::all();

        if (!$posts->count()) {
            $this->command->info('No posts - no comments.');

            return;
        }

        $maxCommentCount = max($this->command->ask('How do you want max comment in each post ?', 30), 0);

        if (!$maxCommentCount) {
            $this->command->info('You choose no comments :(');

            return;
        }

        $users = User::all(['id']);

        $posts->each(static function (BlogPost $post) use ($users, $maxCommentCount) {
            $post->comments()
                ->saveMany(
                    Comment::factory(rand(0, $maxCommentCount))
                        ->state(
                            new Sequence(
                                fn(Sequence $sequence) => ['user_id' => rand(0, 1) ? null : $users->random()->id]
                            )
                        )
                        ->state(
                            new Sequence(
                                function (Sequence $sequence) use ($post) {
                                    $date = fake()->dateTimeBetween($post->created_at);

                                    return [
                                        Comment::CREATED_AT => $date,
                                        Comment::UPDATED_AT => $date,
                                    ];
                                }
                            )
                        )
                        ->make()
                );
        });
    }
}
