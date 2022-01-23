<?php

namespace App\Reports;

use App\Reports\Contracts\ReportContract;

class ReportFactory
{
    public static function make(string $report, string $name, array $filters): ReportContract
    {
        return new $report($name, $filters);
    }
}
