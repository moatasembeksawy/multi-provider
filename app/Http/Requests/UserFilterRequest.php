<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UserFilterRequest extends ApiRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules(): array
    {
        return [
            'provider' => 'sometimes|string|in:DataProviderX,DataProviderY',
            'status' => 'sometimes|string|in:authorised,decline,refunded',
            'balanceMin' => 'sometimes|numeric|min:0',
            'balanceMax' => 'sometimes|numeric|gt:balanceMin',
            'currency' => 'sometimes|string|size:3'
        ];
    }

    public function messages()
    {
        return [
            'provider.in' => 'Provider must be either DataProviderX or DataProviderY.',
            'status.in' => 'Status must be either authorised, decline, or refunded.',
            'balanceMin.numeric' => 'Balance minimum must be a number.',
            'balanceMax.numeric' => 'Balance maximum must be a number.',
            'balanceMin.min' => 'Balance minimum must be at least 0.',
            'balanceMax.min' => 'Balance maximum must be at least 0.',
            'currency.in' => 'Currency must be either USD or AED.',
        ];
    }
}
