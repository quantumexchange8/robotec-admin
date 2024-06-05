<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditClientRequest extends FormRequest
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
        $userId = $this->input('id');

        return [
            'name' => 'string|max:255',
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'phone' => [
                'numeric',
                Rule::unique('users', 'phone')->where(function ($query) {
                    $dialCode = $this->input('dial_code')['value'];
                    $phone = $this->input('phone');
                    $combinedPhoneNumber = (string)$dialCode . (string)$phone;
                    return $query->where('phone', $combinedPhoneNumber);
                })->ignore($userId),
            ],
            'wallet_address' => [
                'string',
                'min:8',
                'max:255',
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
            ],
        ];
    }
            
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'wallet_address' => 'Wallet Address',
        ];
    }

}
