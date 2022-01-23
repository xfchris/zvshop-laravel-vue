<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Helpers\ReportHelper;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\ExportCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class SalesReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public array $request,
        public User $user,
        public string $title,
    ) {
    }

    public function handle(): void
    {
        $name = 'sales_' . ReportHelper::randomNameReports() . $this->user->id . '.pdf';
        $this->generateSalesReport($name);
        $url = route('products.exportDownload', [trim(config('constants.report_directory'), '/'), $name]);
        $this->user->notify(new ExportCompletedNotification($this->user, $this->title, $url));
    }

    private function generateSalesReport(string $name): void
    {
        $rangeDate = ReportHelper::rangeDate($this->request);
        $salesProducts = Payment::select('products')->where('status', AppConstants::APPROVED)->whereBetween('updated_at', $rangeDate)->get();
        $products = ReportHelper::groupHistoryProducts($salesProducts);

        if ($this->request['category_id']) {
            $products = $products->filter(fn ($item) => ($item['category_id'] == $this->request['category_id']));
        }
        $data = [
            'name' => $name,
            'products' => $products,
            'start_date' => $this->request['start_date'],
            'end_date' => $this->request['end_date'],
        ];

        $pdf = PDF::loadView('reports.layouts.pdf_sales', $data);
        Storage::put(config('constants.report_directory') . $data['name'], $pdf->output());
    }
}
