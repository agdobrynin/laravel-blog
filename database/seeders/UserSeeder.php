<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\CacheTagsEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

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

        Cache::tags(CacheTagsEnum::USER_GROUP->value)->flush();

        User::factory($userCount)->create();

        if ($this->command->confirm('Do you want add Site admin?', true)) {
            /** @var User $admin */
            $admin = User::factory(['email' => 'admin@example.com'])->create();

            $adminRole = Role::where('slug', RolesEnum::ADMIN)->first();

            if (!$adminRole) {
                $adminRole = Role::create('admin', RolesEnum::ADMIN);
            }

            $admin->attachRole($adminRole);

            $this->command->info('Create admin user with email ' . $admin->email);
        }
    }
}
