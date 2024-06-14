<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletAdjustmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can change this based on your authorization logic
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|gt:0',
        ];

    }
            
    public function attributes(): array
    {
        return [
            'amount' => trans('public.amount'),
        ];
    }
}
