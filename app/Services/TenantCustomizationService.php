<?php

namespace App\Services;

use App\Models\Tenant;

class TenantCustomizationService
{
    public function getCustomization(Tenant $tenant, string $key, $default = null)
    {
        return cache()->remember(
            "tenant.{$tenant->id}.customizations.{$key}",
            now()->addDay(),
            function () use ($tenant, $key, $default) {
                $customizations = $tenant->customizations ?? [];
                return $customizations[$key] ?? $default;
            }
        );
    }

    public function setCustomization(Tenant $tenant, string $key, $value): void
    {
        $customizations = $tenant->customizations ?? [];
        $customizations[$key] = $value;
        $tenant->update(['customizations' => $customizations]);
    }

    public function removeCustomization(Tenant $tenant, string $key): void
    {
        $customizations = $tenant->customizations ?? [];
        unset($customizations[$key]);
        $tenant->update(['customizations' => $customizations]);
    }
} 