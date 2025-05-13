<?php

namespace App\Console\Commands;

use App\Services\TenantUpdateService;
use Illuminate\Console\Command;
use App\Models\Tenant;

class TenantUpdateCommand extends Command
{
    protected $signature = 'tenants:update 
                            {version : The version to push to tenants}
                            {--tenant= : Apply to specific tenant (ID)}
                            {--all : Apply to all tenants}
                            {--apply : Immediately apply the update}';

    protected $description = 'Manage tenant updates';

    public function handle(TenantUpdateService $updateService)
    {
        $version = $this->argument('version');
        
        if ($this->option('all')) {
            $this->info("Pushing update to all tenants...");
            $updateService->pushUpdateToAllTenants($version);
            $this->info("Update pushed to all tenants.");
            
            if ($this->option('apply')) {
                $this->info("Applying updates to all tenants...");
                Tenant::all()->each(function ($tenant) use ($updateService) {
                    $this->applyUpdateToTenant($updateService, $tenant);
                });
            }
        } elseif ($tenantId = $this->option('tenant')) {
            $tenant = Tenant::findOrFail($tenantId);
            $this->info("Pushing update to tenant {$tenant->name}...");
            $updateService->pushUpdateToTenant($tenant, $version);
            
            if ($this->option('apply')) {
                $this->applyUpdateToTenant($updateService, $tenant);
            }
        } else {
            $this->error('You must specify either --all or --tenant');
            return 1;
        }
        
        return 0;
    }
    
    protected function applyUpdateToTenant(TenantUpdateService $service, Tenant $tenant)
    {
        $this->info("Applying update to tenant {$tenant->name}...");
        if ($service->applyUpdate($tenant)) {
            $this->info("Update applied successfully to {$tenant->name}");
        } else {
            $this->error("No pending update for {$tenant->name}");
        }
    }
} 