<?php

namespace App\Services\Report;

use App\Helpers\ReportHelper;
use App\Jobs\ReportsJob;
use App\Reports\GeneralReport;
use App\Reports\ReportFactory;
use App\Reports\SalesReport;
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
