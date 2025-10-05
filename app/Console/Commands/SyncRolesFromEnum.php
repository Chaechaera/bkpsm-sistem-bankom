<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncRolesFromEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:sync-from-enum {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users.role (enum) into spatie roles. Use --dry-run first.';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $dry = $this->option('dry-run');
        $availableRoles = Role::pluck('name')->toArray();

        foreach (User::all() as $user) {
            $enumRole = $user->enumRole();
            if (!$enumRole || !in_array($enumRole, $availableRoles)) continue;

            if ($dry) {
                $this->line("[DRY] Would assign {$enumRole} to {$user->email}");
            } else {
                $user->syncRoles([$enumRole]);
                $this->line("Assigned {$enumRole} to {$user->email}");
            }
        }

        return 0;
    }
}
