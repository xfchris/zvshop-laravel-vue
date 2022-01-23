<?php

namespace App\Jobs;

use App\Helpers\ReportHelper;
use App\Models\User;
use App\Notifications\ExportCompletedNotification;
use App\Reports\SalesReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SalesReportJob implements ShouldQueue
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
        $name = 'sales_' . ReportHelper::randomNameReports() . $this->user->id . '.pdf';
        $url = route('products.exportDownload', [trim(config('constants.report_directory'), '/'), $name]);

        (new SalesReport($name, $this->request))->generate();
        $this->user->notify(new ExportCompletedNotification($this->user, 'sales', $url));
    }
}
