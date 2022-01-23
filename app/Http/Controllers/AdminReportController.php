<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Services\Report\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function __construct(
        public ReportService $reportService
    ) {
    }

    public function index(): View
    {
        return view('reports.index');
    }

    public function general(ReportRequest $request): RedirectResponse
    {
        $this->authorize('can', 'reports_general');
        $this->reportService->generalReport($request);
        return redirect()->route('admin.reports.index')->with('success', trans('app.reports.notify_report_generated') . Auth::user()->email);
    }

    public function sales(ReportRequest $request): RedirectResponse
    {
        $this->authorize('can', 'reports_sales');
        $this->reportService->salesReport($request);
        return redirect()->route('admin.reports.index')->with('success', trans('app.reports.notify_report_generated') . Auth::user()->email);
    }
}
