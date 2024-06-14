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
        $userID = $this->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique(User::class)->ignore($userID)],
            'dial_code' => ['required'],
            'phone' => ['required'],
            'usdt_address' => ['string','min:8','max:255'],
        ];
    }
                
    public function attributes(): array
    {
        return [
            'name' => trans('public.name'),
            'email' => trans('public.email'),
            'dial_code' => trans('public.dial_code'),
            'phone' => trans('public.phone_number'),
            'usdt_address' => trans('public.usdt_address'),
        ];
    }

}
