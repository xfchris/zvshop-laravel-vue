<?php

namespace App\Http\Requests;

use App\Constants\AppConstants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uniqueRule = Rule::unique('users')->where(function ($query) {
            return $query->where('id_type', $this->input('id_type'))
                        ->where('id_number', $this->input('id_number'))
                        ->where('id', '<>', $this->route('user')->id);
        });

        return [
            'name' => 'required|max:80',
            'id_type' => ['required', Rule::in(AppConstants::TYPE_DOCUMENT)],
            'id_number' => ['required', 'integer', 'min:1', 'max:99999999999', $uniqueRule],
            'address' => ['nullable', 'max:180'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
