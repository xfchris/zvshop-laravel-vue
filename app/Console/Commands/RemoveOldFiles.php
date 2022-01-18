<?php

namespace App\Console\Commands;

use App\Events\LogGeneralEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveOldFiles extends Command
{
    protected $signature = 'remove:old_reports';
    protected $description = 'Remove old reports';

    public function handle(): int
    {
        $filesRemoved = [];
        collect(Storage::listContents())->each(function ($file) {
            if (
                    $file['timestamp'] < now()->subDays(config('constants.reports_expiration_days'))->getTimestamp() &&
                    $file['extension'] == 'xlsx'
                ) {
                Storage::delete($file['path']);
                $filesRemoved[] = $file['path'];
            }
        });
        LogGeneralEvent::dispatchIf(count($filesRemoved), 'info', 'Removed old files: ' . json_encode($filesRemoved));
        return Command::SUCCESS;
    }
}
