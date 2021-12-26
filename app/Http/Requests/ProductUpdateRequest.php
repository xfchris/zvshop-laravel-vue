<?php

namespace App\Http\Requests;

class ProductUpdateRequest extends ProductStoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        array_walk($rules, function (&$item) {
            if ($item[0] == 'required') {
                $item[0] = 'filled';
            }
            return $item;
        });

        return $rules;
    }
}
