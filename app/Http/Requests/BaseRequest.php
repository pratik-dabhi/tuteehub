<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\GeneralError;

class BaseRequest extends FormRequest
{
    /**
     * Return JSON on failed validation using GeneralError
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $response = (new GeneralError([
            'message' => 'Validation failed',
            'data'  => $errors,
            'status'  => 422,
        ]))->response();

        throw new ValidationException($validator, $response);
    }

    /**
     * Return JSON on failed authorization
     */
    protected function failedAuthorization()
    {
        $response = (new GeneralError([
            'message' => 'This action is unauthorized',
            'status'  => 403,
        ]))->response();

        abort($response->getStatusCode(), $response->getData()->message);
    }
}
