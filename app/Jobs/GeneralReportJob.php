<?php

namespace App\Jobs;

use App\Helpers\ReportHelper;
use App\Models\User;
use App\Notifications\ExportCompletedNotification;
use App\Reports\GeneralReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneralReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public array $request,
        public User $user,
    ) {
    }

    public function handle(): void
    {
        $name = 'general_' . ReportHelper::randomNameReports() . $this->user->id . '.pdf';
        $url = route('products.exportDownload', [trim(config('constants.report_directory'), '/'), $name]);

        (new GeneralReport($name, $this->request))->generate();
        $this->user->notify(new ExportCompletedNotification($this->user, 'general information', $url));
    }
}
