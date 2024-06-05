<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddClientRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => [
                'required',
                'numeric',
                // Rule to ensure the phone number is unique
                Rule::unique('users', 'phone')->where(function ($query) {
                    $dialCode = $this->input('dial_code')['value'];
                    $phone = $this->input('phone');
                    $combinedPhoneNumber = (string)$dialCode . (string)$phone; // Ensure both values are strings
                    return $query->where('phone', $combinedPhoneNumber);
                }),
            ],
            // 'upline' => 'required',
        ];
    }
            
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone Number',
            // 'upline' => 'Upline',
        ];
    }

}
