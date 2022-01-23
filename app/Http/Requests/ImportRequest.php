<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['file' => ['mimes:xlsx', 'max:' . config('constants.file_max_size')]];
    }

    protected function failedValidation(Validator $validator): void
    {
        $response = ['code' => 422, 'errors' => $validator->errors()];
        throw new ValidationException($validator, new JsonResponse($response, $response['code']));
    }
}
