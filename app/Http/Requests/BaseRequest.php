<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    protected function formatErrors(Validator $validator)
    {
        $messages = $validator->errors()->getMessages();
        $formattedErrors = [];

        foreach ($messages as $field => $message) {
            $formattedErrors[$field] = $message[0];
        }

        return [
            'message' => 'Error de validación',
            'errors' => $formattedErrors,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($this->formatErrors($validator), 422));
    }
}
