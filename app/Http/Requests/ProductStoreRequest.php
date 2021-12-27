<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:120'],
            'images.*' => ['mimes:jpeg,png,jpg,gif', 'max:' . config('constants.image_max_size')],
            'images' => ['max:5'],
            'category_id' => ['required', 'exists:categories,id'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }
}
