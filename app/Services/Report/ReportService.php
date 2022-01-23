<?php

namespace App\Services\Report;

use App\Factories\Reports\GeneralReport;
use App\Factories\Reports\ReportFactory;
use App\Factories\Reports\SalesReport;
use App\Helpers\ReportHelper;
use App\Jobs\ReportsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    public function generalReport(Request $request): void
    {
        $filename = 'general_' . ReportHelper::createReportName() . Auth::user()->id . '.pdf';
        $filters = $request->all();

        ReportsJob::dispatch(
            Auth::user(),
            'general information',
            ReportFactory::make(GeneralReport::class, $filename, $filters)
        );
    }

    public function salesReport(Request $request): void
    {
        $filename = 'sales_' . ReportHelper::createReportName() . Auth::user()->id . '.pdf';
        $filters = $request->all();

        ReportsJob::dispatch(
            Auth::user(),
            'sales',
            ReportFactory::make(SalesReport::class, $filename, $filters)
        );
    }
}
