<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

abstract class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        if (!empty($errors)) {
            $transformedErrors = [];
            foreach ($errors as $field => $message) {
                $transformedErrors[] = [
                    $field => $message[0]
                ];
            }

            throw new HttpResponseException(
                response()->json(
                    [
                        'status' => 'error',
                        'message' => 'validation error',
                        'data' => $transformedErrors
                    ],
                    JsonResponse::HTTP_BAD_REQUEST
                )
            );
        }
    }
}
