<?php

namespace App\Filters;

class Criteria
{
    private string|array|null $value;

    public function __construct(string|array|null $value)
    {
        $this->value = $value;
    }

    public function value(): string|array|null
    {
        return $this->value;
    }
}
