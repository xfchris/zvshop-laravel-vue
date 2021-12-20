<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserActiveInactiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'banned_until' => 'present|nullable|integer|between:5,3650', // 10 years
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $response = new JsonResponse(['status' => 'error', 'errors' => $validator->errors()], 422);
        throw new ValidationException($validator, $response);
    }
}
