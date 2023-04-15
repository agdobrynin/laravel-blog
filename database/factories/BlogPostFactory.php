<?php

namespace Database\Factories;

use App\Models\BlogPost;
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
        $date = fake()->dateTimeBetween('-180 days');
        return [
            'title' => fake('ru_RU')->realTextBetween(5, 50, 1),
            'content' => fake('ru_RU')->paragraphs(rand(2, 8), true),
            BlogPost::CREATED_AT => $date,
            BlogPost::UPDATED_AT => $date,
        ];
    }
}
