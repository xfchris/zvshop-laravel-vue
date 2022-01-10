<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentPayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_receive' => 'required|max:80',
            'address' => ['nullable', 'max:300'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
