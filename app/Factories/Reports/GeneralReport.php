<?php

namespace App\Factories\Reports;

use App\Constants\AppConstants;
use App\Factories\Reports\Contracts\ReportContract;
use App\Helpers\ReportHelper;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use PDF;

class GeneralReport extends ReportContract
{
    public function generate(): bool
    {
        $rangeDate = ReportHelper::getRangeDate($this->filters);
        $filtersApproved = [
            'status' => AppConstants::APPROVED,
            'updated_at' => $rangeDate,
        ];
        $filtersRejected = [
            'status' => AppConstants::REJECTED,
            'updated_at' => $rangeDate,
        ];

        $data = [
            'name' => $this->name,
            'start_date' => $this->filters['start_date'],
            'end_date' => $this->filters['end_date'],
            'totalPaymentApproved' => Payment::filter($filtersApproved)->count(),
            'totalPricePaymentApproved' => Payment::filter($filtersApproved)->sum('totalAmount'),
            'totalPaymentRejected' => Payment::filter($filtersRejected)->count(),
            'totalPricePaymentRejected' => Payment::filter($filtersRejected)->sum('totalAmount'),
            'userBuyers' => Order::filter($filtersApproved)->groupBy('user_id')->get(),
        ];
        $pdf = PDF::loadView('reports.layouts.pdf_general', $data);
        return Storage::put(config('constants.report_directory') . $data['name'], $pdf->output());
    }
}
