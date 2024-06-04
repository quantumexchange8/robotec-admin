<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePammRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // You might need to adjust this based on your authentication logic
    }

    public function rules(): array
    {
        return [
            'pamm' => [
                'required',
                'regex:/^[+-]\d*\.?\d+$/',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'pamm' => 'Pamm Return',
        ];
    }

}
