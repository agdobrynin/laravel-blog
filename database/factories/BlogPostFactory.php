<?php

namespace Database\Factories;

use App\Models\BlogPost as Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake('ru_RU')->realTextBetween(5, 50, 1),
            'content' => fake('ru_RU')->realTextBetween(390, 700, 1),
        ];
    }
}
