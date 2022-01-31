<?php

namespace App\Filters\ModelFilters;

use App\Filters\Conditions\RangeUpdateDate;
use App\Filters\Conditions\Status;
use App\Filters\Filter;
use App\Models\Payment;

class PaymentFilters extends Filter
{
    protected string $model = Payment::class;
    protected array $applicableConditions = [
        'updated_at' => RangeUpdateDate::class,
        'status' => Status::class,
    ];
}
