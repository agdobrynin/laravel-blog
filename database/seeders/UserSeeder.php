<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCount = (int)max($this->command->ask('How many users do you like?', 10), 0);

        if (!$userCount) {
            $this->command->info('You choose zero users :(');

            return;
        }

        User::factory($userCount)->create();
    }
}
