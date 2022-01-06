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
            return $query->where('document_type', $this->input('document_type'))
                        ->where('document', $this->input('document'))
                        ->where('id', '<>', $this->route('user')->id);
        });

        return [
            'name' => 'required|max:80',
            'document_type' => ['required', Rule::in(AppConstants::TYPE_DOCUMENT)],
            'document' => ['required', 'integer', 'min:1', 'max:99999999999', $uniqueRule],
            'address' => ['nullable', 'max:300'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
