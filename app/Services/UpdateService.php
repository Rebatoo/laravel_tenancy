<?php

namespace App\Services;

use App\Models\Update;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateService
{
    public function checkForUpdates()
    {
        try {
            $latestUpdate = Update::published()
                                ->latest()
                                ->first();

            if (!$latestUpdate) {
                return null;
            }

            // Cache the latest update for 1 hour
            Cache::put('latest_update', $latestUpdate, now()->addHour());

            return $latestUpdate;
        } catch (\Exception $e) {
            Log::error('Error checking for updates: ' . $e->getMessage());
            return null;
        }
    }

    public function getUpdateDetails($version)
    {
        return Update::where('version', $version)
                    ->published()
                    ->first();
    }

    public function markUpdateAsInstalled($version)
    {
        $update = Update::where('version', $version)->first();
        
        if ($update) {
            // Here you can add logic to track which tenants have installed the update
            // For example, you could create a pivot table to track update installations
            return true;
        }

        return false;
    }

    public function getRequiredUpdates()
    {
        return Update::required()
                    ->published()
                    ->latest()
                    ->get();
    }
} 