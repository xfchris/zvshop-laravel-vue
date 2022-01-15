<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return self::rulesRequest();
    }

    public static function rulesRequest(): array
    {
        $rules = ProductStoreRequest::rulesRequest();
        array_walk($rules, function (&$item) {
            if ($item[0] == 'required') {
                $item[0] = 'filled';
            }
            return $item;
        });
        return $rules;
    }
}
