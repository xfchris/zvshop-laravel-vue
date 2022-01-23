<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Helpers\ReportHelper;
use App\Models\Order;
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

class GeneralReportJob implements ShouldQueue
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
        $name = 'general_' . ReportHelper::randomNameReports() . $this->user->id . '.pdf';
        $this->generateGeneralReport($name);
        $url = route('products.exportDownload', [trim(config('constants.report_directory'), '/'), $name]);
        $this->user->notify(new ExportCompletedNotification($this->user, $this->title, $url));
    }

    private function generateGeneralReport(string $name): void
    {
        $rangeDate = ReportHelper::rangeDate($this->request);
        $data = [
            'start_date' => $this->request['start_date'],
            'end_date' => $this->request['end_date'],
            'name' => $name,
            'totalPaymentApproved' => Payment::where('status', AppConstants::APPROVED)->whereBetween('updated_at', $rangeDate)->count(),
            'totalPricePaymentApproved' => Payment::where('status', AppConstants::APPROVED)->whereBetween('updated_at', $rangeDate)->sum('totalAmount'),
            'totalPaymentRejected' => Payment::where('status', AppConstants::REJECTED)->whereBetween('updated_at', $rangeDate)->count(),
            'totalPricePaymentRejected' => Payment::where('status', AppConstants::REJECTED)->whereBetween('updated_at', $rangeDate)->sum('totalAmount'),
            'userBuyers' => Order::select('user_id')->with('user:id,name,surname,email')
                                 ->whereBetween('updated_at', $rangeDate)->where('status', AppConstants::APPROVED)->groupBy('user_id')->get(),
        ];

        $pdf = PDF::loadView('reports.layouts.pdf_general', $data);
        Storage::put(config('constants.report_directory') . $data['name'], $pdf->output());
    }
}
