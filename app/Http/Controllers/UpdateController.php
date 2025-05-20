<?php

namespace App\Http\Controllers;

use App\Services\UpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UpdateController extends Controller
{
    protected $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }

    public function check()
    {
        $latestUpdate = Cache::remember('latest_update', 3600, function () {
            return $this->updateService->checkForUpdates();
        });

        if (!$latestUpdate) {
            return response()->json(['message' => 'No updates available'], 200);
        }

        return response()->json([
            'update' => $latestUpdate,
            'is_required' => $latestUpdate->is_required
        ]);
    }

    public function install($version)
    {
        $update = $this->updateService->getUpdateDetails($version);

        if (!$update) {
            return response()->json(['message' => 'Update not found'], 404);
        }

        if ($this->updateService->markUpdateAsInstalled($version)) {
            return response()->json(['message' => 'Update installed successfully']);
        }

        return response()->json(['message' => 'Failed to install update'], 500);
    }

    public function required()
    {
        $requiredUpdates = $this->updateService->getRequiredUpdates();
        
        return response()->json([
            'updates' => $requiredUpdates
        ]);
    }
}
