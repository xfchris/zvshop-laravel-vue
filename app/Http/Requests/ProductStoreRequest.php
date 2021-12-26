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
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:' . config('constants.image_max_size')],
            'images_alt.*' => ['mimes:jpeg,png,jpg,gif', 'max:' . config('constants.image_max_size')],
            'category_id' => ['required', 'exists:categories,id'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }
}
