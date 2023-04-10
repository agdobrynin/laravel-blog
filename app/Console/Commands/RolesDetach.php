<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class RolesDetach extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:detach {email} {slug*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detach roles for user';

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

        $slugs = $this->argument('slug');
        $foundRoles = Role::whereIn('slug', $slugs)->get();

        $rolesTitles = $foundRoles->implode('slug', ', ');

        if ($this->confirm('Detach roles '.$rolesTitles.' from user '.$user->email, true)) {
            foreach ($foundRoles as $role) {
                $user->detachRole($role);
            }

            $this->table(
                ['slug', 'name'],
                $user->roles()->get(['slug', 'name'])->makeHidden('pivot')->toArray()
            );
        }
    }
}
