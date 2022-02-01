<?php

namespace App\Factories\Reports;

use App\Constants\AppConstants;
use App\Factories\Reports\Contracts\ReportContract;
use App\Helpers\ReportHelper;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use PDF;

class SalesReport extends ReportContract
{
    public function generate(): bool
    {
        $rangeDate = ReportHelper::getRangeDate($this->filters);
        $filtersApproved = [
            'status' => AppConstants::APPROVED,
            'updated_at' => $rangeDate,
        ];
        $salesProducts = Payment::filter($filtersApproved)->select('products')->get();
        $products = ReportHelper::groupByProductHistory($salesProducts);

        if ($this->filters['category_id']) {
            $products = $products->filter(fn ($item) => ($item['category_id'] == $this->filters['category_id']));
        }
        $data = [
            'name' => $this->name,
            'products' => $products,
            'start_date' => $this->filters['start_date'],
            'end_date' => $this->filters['end_date'],
        ];
        $pdf = PDF::loadView('reports.layouts.pdf_sales', $data);
        return Storage::put(config('constants.report_directory') . $data['name'], $pdf->output());
    }
}
