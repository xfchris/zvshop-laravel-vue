<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images_alt.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ];
    }
}
