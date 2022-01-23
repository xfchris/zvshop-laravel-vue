<?php

namespace App\Reports;

use App\Constants\AppConstants;
use App\Helpers\ReportHelper;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use PDF;

class SalesReport
{
    public function __construct(
        public string $name,
        public array $filters
    ) {
    }

    public function generate(): bool
    {
        $rangeDate = ReportHelper::rangeDate($this->filters);
        $salesProducts = Payment::select('products')->where('status', AppConstants::APPROVED)->whereBetween('updated_at', $rangeDate)->get();
        $products = ReportHelper::groupHistoryProducts($salesProducts);

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
