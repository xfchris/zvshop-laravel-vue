<?php

namespace App\Reports;

use App\Constants\AppConstants;
use App\Helpers\ReportHelper;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use PDF;

class GeneralReport
{
    public function __construct(
        public string $name,
        public array $filters
    ) {
    }

    public function generate(): bool
    {
        $rangeDate = ReportHelper::rangeDate($this->filters);
        $data = [
            'name' => $this->name,
            'start_date' => $this->filters['start_date'],
            'end_date' => $this->filters['end_date'],
            'totalPaymentApproved' => Payment::where('status', AppConstants::APPROVED)->whereBetween('updated_at', $rangeDate)->count(),
            'totalPricePaymentApproved' => Payment::where('status', AppConstants::APPROVED)->whereBetween('updated_at', $rangeDate)->sum('totalAmount'),
            'totalPaymentRejected' => Payment::where('status', AppConstants::REJECTED)->whereBetween('updated_at', $rangeDate)->count(),
            'totalPricePaymentRejected' => Payment::where('status', AppConstants::REJECTED)->whereBetween('updated_at', $rangeDate)->sum('totalAmount'),
            'userBuyers' => Order::select('user_id')->with('user:id,name,surname,email')
                                 ->whereBetween('updated_at', $rangeDate)->where('status', AppConstants::APPROVED)->groupBy('user_id')->get(),
        ];
        $pdf = PDF::loadView('reports.layouts.pdf_general', $data);
        return Storage::put(config('constants.report_directory') . $data['name'], $pdf->output());
    }
}
