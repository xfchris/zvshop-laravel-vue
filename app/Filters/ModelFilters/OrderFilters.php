<?php

namespace App\Filters\ModelFilters;

use App\Filters\Conditions\RangeUpdateDate;
use App\Filters\Conditions\Status;
use App\Filters\Filter;
use App\Models\Order;

class OrderFilters extends Filter
{
    protected string $model = Order::class;
    protected array $applicableConditions = [
        'updated_at' => RangeUpdateDate::class,
        'status' => Status::class,
    ];

    protected function select(): Filter
    {
        $this->query->select('user_id');
        return $this;
    }

    protected function joins(): Filter
    {
        $this->query->with('user:id,name,surname,email');
        return $this;
    }
}
