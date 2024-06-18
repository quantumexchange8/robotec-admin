<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ApproveWithdrawalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can change this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'from_wallet_address' => ['required','string','max:255',],
            'txn_hash' => ['required','string','max:255'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
            
    public function attributes(): array
    {
        return [
            'from_wallet_address' => trans('public.usdt_address'),
            'txn_hash' => trans('public.txn_hash'),
            'remarks' => trans('public.description'),
        ];
    }
}
