<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantUpdateService;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function checkForUpdates()
    {
        $tenant = tenant();
        return response()->json([
            'current_version' => $tenant->current_version,
            'pending_version' => $tenant->pending_version,
            'update_available' => !is_null($tenant->pending_version),
        ]);
    }

    public function applyUpdate(TenantUpdateService $updateService)
    {
        if (!$updateService->applyUpdate(tenant())) {
            return response()->json(['message' => 'No update available'], 400);
        }

        return response()->json(['message' => 'Update applied successfully']);
    }
} 