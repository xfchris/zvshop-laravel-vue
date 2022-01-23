<?php

namespace App\Reports\Contracts;

abstract class ReportContract
{
    public function __construct(
        public string $name,
        public array $filters
    ) {
    }

    abstract public function generate(): bool;

    public function getDownloadUrl(): string
    {
        return route('products.exportDownload', [trim(config('constants.report_directory'), '/'), $this->name]);
    }
}
