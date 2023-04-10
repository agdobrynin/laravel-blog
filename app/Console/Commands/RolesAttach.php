<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class RolesAttach extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:attach {email} {slug*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach roles for user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->argument('email');
        /** @var User|null $user */
        $user = User::where(['email' => $email])->first();

        if (!$user) {
            $this->error(sprintf('User with email "%s" not found', $email));

            return;
        }

        $allRoles = Role::all();
        $rolesDiff = $allRoles->diff($user->getRoles());

        if ($rolesDiff->count() === 0) {
            $this->info('User has all roles');
            $this->printRoles($user);

            return;
        }

        if ($inputRoleSlugs = $this->argument('slug')) {
            $existSlugs = $rolesDiff->pluck('slug')->all();
            $inputRoleSlugsFound = array_filter($inputRoleSlugs, fn($slug) => \in_array($slug, $existSlugs));

            if($inputRoleSlugsFound &&
                $this->confirm('Found roles "'.implode(', ', $inputRoleSlugsFound).'". Attach this roles?', true)) {

                $rolesDiff->each(function (Role $role) use ($inputRoleSlugsFound, $user) {
                    if (\in_array($role->slug, $inputRoleSlugsFound)) {
                        $user->attachRole($role);
                    }
                });

                $this->printRoles($user);

                return;
            } else {
                $this->warn('Not found available roles from input');

                return;
            }
        }

        $roles = array_combine(
            $rolesDiff->pluck('slug')->all(),
            $rolesDiff->pluck('name')->all(),
        );

        $selectRoles = $this->choice(
            'Select roles for user '.$email,
            $roles,
            null,
            null,
            true
        );

        Role::whereIn('slug', $selectRoles)->each(function (Role $role) use($user) {
            $user->attachRole($role);
        });

        $this->printRoles($user);
    }

    private function printRoles(User $user): void
    {
        $this->info('User '.$user->email.' has roles:');
        $this->table(
            ['slug', 'name'],
            $user->roles()->get(['slug', 'name'])->makeHidden('pivot')->toArray()
        );
    }
}
