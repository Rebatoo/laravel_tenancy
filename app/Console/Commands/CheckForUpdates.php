<?php

namespace App\Console\Commands;

use App\Services\UpdateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckForUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updates:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for available updates';

    protected $updateService;

    public function __construct(UpdateService $updateService)
    {
        parent::__construct();
        $this->updateService = $updateService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $latestUpdate = $this->updateService->checkForUpdates();

            if ($latestUpdate) {
                $this->info("New update available: {$latestUpdate->version}");
                $this->info("Title: {$latestUpdate->title}");
                $this->info("Description: {$latestUpdate->description}");
                
                if ($latestUpdate->is_required) {
                    $this->warn('This is a required update!');
                }
            } else {
                $this->info('No new updates available.');
            }
        } catch (\Exception $e) {
            Log::error('Error checking for updates: ' . $e->getMessage());
            $this->error('An error occurred while checking for updates.');
        }
    }
}
