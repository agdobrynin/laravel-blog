<?php

namespace App\Console\Commands;

use App\Enums\RolesEnum;
use App\Models\Role;
use Illuminate\Console\Command;

class RolesAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:add {slug} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new role';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var Role $role */
        $role = Role::make(
            [
                'slug' => $this->argument('slug'),
                'name' => $this->argument('name')
            ]
        );

        if ($foundRole = Role::where('slug', $role->slug)->first()) {
            $this->error('Role with slug "' . $foundRole->slug . '" already exist');

            return;
        }

        if ($this->confirm('Add new role with slug "' . $role->slug . '"', true)) {
            if ($role->save()) {
                $this->warn('Please add new role slug "' . $role->slug . '" to enum class ' . RolesEnum::class);
            }

            $this->table(
                ['slug', 'name'],
                Role::all(['slug', 'name']),
            );
        }
    }
}
