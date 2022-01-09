<?php

namespace App\Http\Requests;

use App\Constants\AppConstants;
use App\Rules\UniqueDocument;
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
        return [
            'name' => 'required|max:80',
            'document_type' => ['required', Rule::in(AppConstants::TYPE_DOCUMENT)],
            'document' => ['required', 'integer', 'min:1', 'max:99999999999',
                new UniqueDocument($this->input('document_type'), $this->input('document'), $this->route('user')->id), ],
            'address' => ['nullable', 'max:300'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
