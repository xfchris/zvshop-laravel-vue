<?php

namespace App\Services\Report;

use App\Events\LogGeneralEvent;
use App\Jobs\GeneralReportJob;
use App\Jobs\SalesReportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    public function generalReport(Request $request): void
    {
        GeneralReportJob::dispatch($request->all(), Auth::user(), 'general information');
        LogGeneralEvent::dispatch('info', 'A general report has been created by the user: Name=' . Auth::user()->name . ' id=' . Auth::user()->id);
    }

    public function salesReport(Request $request): void
    {
        SalesReportJob::dispatch($request->all(), Auth::user(), 'sales');
        LogGeneralEvent::dispatch('info', 'A sales report has been created by the user: Name=' . Auth::user()->name . ' id=' . Auth::user()->id);
    }
}
