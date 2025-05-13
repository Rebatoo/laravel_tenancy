<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Facades\Tenancy;

class TenantUpdateService
{
    public function pushUpdateToAllTenants(string $version)
    {
        Tenant::all()->each(function ($tenant) use ($version) {
            $this->pushUpdateToTenant($tenant, $version);
        });
    }

    public function pushUpdateToTenant(Tenant $tenant, string $version)
    {
        $tenant->update(['pending_version' => $version]);
        
        // Optionally notify tenant admin about available update
        // $this->notifyTenantAboutUpdate($tenant, $version);
    }

    public function applyUpdate(Tenant $tenant)
    {
        if (!$tenant->pending_version) {
            return false;
        }

        Tenancy::initialize($tenant);

        try {
            // Run migrations for the tenant
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            // Update tenant's version
            $tenant->update([
                'current_version' => $tenant->pending_version,
                'pending_version' => null
            ]);

            return true;
        } finally {
            Tenancy::end();
        }
    }
} 