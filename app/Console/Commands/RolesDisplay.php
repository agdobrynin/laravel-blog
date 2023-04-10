<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\TableSeparator;

class RolesDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:display {slug?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display exist roles';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($slugs = $this->argument('slug')) {
            $this->info('Find all users with role "' . implode(', ', $slugs) . '"');
            $this->newLine();
            $users =
                User::whereHas('roles', fn($q) => $q->whereIn('slug', $slugs))
                    ->with(['roles' => fn($q) => $q->select(['slug', 'name'])])
                    ->get();

            $rows = [];

            for ($ui = 0, $uc = $users->count(); $ui < $uc; $ui++) {
                $roles = $users[$ui]->getRoles();
                $rows[] = [$users[$ui]->email, $users[$ui]->name, $roles[0]->name, $roles[0]->slug];

                for($ri=1, $rc = $roles->count(); $ri < $rc ; $ri++) {
                    $rows[] = ['', '', $roles[$ri]->name, $roles[$ri]->slug];
                }

                if ($ui < $uc - 1) {
                    $rows[] = new TableSeparator;
                }
            }

            $this->table(
                ['email', 'name', 'roles name', 'roles slug'],
                $rows
            );
        } else {
            $this->table(
                ['Role name', 'Role slug'],
                Role::all(['name', 'slug']),
            );
        }
    }
}
