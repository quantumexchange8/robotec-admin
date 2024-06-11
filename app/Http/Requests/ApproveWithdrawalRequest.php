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
            'wallet_address' => ['required','string','max:255',Rule::exists('wallets', 'wallet_address'),],
            'txn_hash' => ['required','string','max:255',Rule::exists('transactions', 'txn_hash'),],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
            
    public function attributes(): array
    {
        return [
            'wallet_address' => 'Wallet Address',
            'txn_hash' => 'Transaction Hash',
            'remarks' => 'Remarks',
        ];
    }
}
