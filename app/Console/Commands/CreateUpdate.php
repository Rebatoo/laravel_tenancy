<?php

namespace App\Console\Commands;

use App\Models\Update;
use Illuminate\Console\Command;

class CreateUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new update';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version = $this->ask('What is the version number? (e.g., 1.0.1)');
        $title = $this->ask('What is the title of the update?');
        $description = $this->ask('What is the description of the update?');
        
        $changes = [];
        $this->info('Enter the changes (one per line). Type "done" when finished:');
        while (true) {
            $change = $this->ask('Change (or "done" to finish)');
            if ($change === 'done') break;
            $changes[] = $change;
        }

        $isRequired = $this->confirm('Is this a required update?', false);
        $type = $this->choice(
            'What type of update is this?',
            ['feature', 'bugfix', 'security'],
            'feature'
        );

        $update = Update::create([
            'version' => $version,
            'title' => $title,
            'description' => $description,
            'changes' => $changes,
            'is_required' => $isRequired,
            'is_published' => true,
            'published_at' => now(),
            'type' => $type
        ]);

        $this->info('Update created successfully!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Version', $update->version],
                ['Title', $update->title],
                ['Description', $update->description],
                ['Type', $update->type],
                ['Required', $update->is_required ? 'Yes' : 'No'],
                ['Changes', implode("\n", $update->changes)],
            ]
        );
    }
}
