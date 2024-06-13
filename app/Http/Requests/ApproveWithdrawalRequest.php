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
            'usdt_address' => ['required','string','max:255',Rule::exists('users', 'usdt_address'),],
            'transaction_number' => ['required','string','max:255',Rule::exists('transactions', 'transaction_number'),],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
            
    public function attributes(): array
    {
        return [
            'usdt_address' => 'USDT Address',
            'transaction_number' => 'Transaction ID',
            'remarks' => 'Remarks',
        ];
    }
}
