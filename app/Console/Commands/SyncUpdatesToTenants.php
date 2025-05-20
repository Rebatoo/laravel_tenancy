<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Update;
use App\Models\Tenant;
use Stancl\Tenancy\Facades\Tenancy;

class SyncUpdatesToTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:sync-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all updates from the central database to all tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updates = Update::all();
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Syncing updates for tenant: {$tenant->id}");
            tenancy()->initialize($tenant);

            foreach ($updates as $update) {
                $exists = Update::where('version', $update->version)->first();
                if (!$exists) {
                    Update::create($update->toArray());
                    $this->info("  - Synced update version: {$update->version}");
                } else {
                    $this->info("  - Update version {$update->version} already exists");
                }
            }

            tenancy()->end();
        }

        $this->info('All updates have been synced to all tenants.');
    }
}
