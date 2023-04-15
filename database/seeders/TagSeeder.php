<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::factory(10)
            ->sequence(
                ['name' => 'Sports'],
                ['name' => 'Travel'],
                ['name' => 'Entertainment'],
                ['name' => 'Development'],
                ['name' => 'Customization'],
                ['name' => 'Ability'],
                ['name' => 'Astrology'],
                ['name' => 'Space'],
                ['name' => 'Auto & moto'],
                ['name' => 'Films & serials'],
            )
            ->create();
    }
}
