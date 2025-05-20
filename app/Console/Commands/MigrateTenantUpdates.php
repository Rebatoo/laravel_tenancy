<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Facades\Tenancy;
use App\Models\Tenant;

class MigrateTenantUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the updates migration for all tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Migrating updates for tenant: {$tenant->id}");
            
            tenancy()->initialize($tenant);
            
            try {
                $this->call('migrate', [
                    '--path' => 'database/migrations/tenant',
                    '--force' => true
                ]);
                $this->info("Successfully migrated updates for tenant: {$tenant->id}");
            } catch (\Exception $e) {
                $this->error("Failed to migrate updates for tenant {$tenant->id}: {$e->getMessage()}");
            }
            
            tenancy()->end();
        }

        $this->info('Completed migrating updates for all tenants');
    }
}
