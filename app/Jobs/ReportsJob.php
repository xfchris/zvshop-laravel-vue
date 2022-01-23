<?php

namespace App\Jobs;

use App\Factories\Reports\Contracts\ReportContract;
use App\Models\User;
use App\Notifications\ExportCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReportsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $name,
        public ReportContract $report
    ) {
    }

    public function handle(): void
    {
        $this->report->generate();
        $this->user->notify(new ExportCompletedNotification($this->user, $this->name, $this->report->getDownloadUrl()));
        Log::log('info', 'A report of ' . $this->name . ' has been created by the user: Name=' . $this->user->name . ' id=' . $this->user->id);
    }
}
