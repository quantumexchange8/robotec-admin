<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\Wallet;
use App\Services\CTraderService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create($referral): Response
    {
        return Inertia::render('Auth/Register', [
            'referral_code' => $referral,
        ]);
    }

    public function firstStep(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'full_name' => 'required|string|max:255|unique:' . User::class . ',name',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|unique:' . User::class . ',phone',
        ];

        $attributes = [
            'email' => trans('public.email'),
            'full_name' => trans('public.full_name'),
            'phone_number' => trans('public.phone_number'),
        ];

        $dial_code = $request->dial_code;
        $phone = $request->phone_number;

        // Remove leading '+' from dial code if present
        $dial_code = ltrim($dial_code, '+');

        // Remove leading '+' from phone number if present
        $phone = ltrim($phone, '+');

        // Check if phone number already starts with dial code
        if (!str_starts_with($phone, $dial_code)) {
            // Concatenate dial code and phone number
            $phone = '+' . $dial_code . $phone;
        } else {
            // If phone number already starts with dial code, use the phone number directly
            $phone = '+' . $phone;
        }

        // Merge the modified phone number back into the request
        $request->merge(['phone_number' => $phone]);

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($attributes);

        if ($request->form_step == 0) {
            $validator->validate();
        }

        return to_route('register', ['referral' => $request->referral_code]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'password' => ['required', 'confirmed', Password::min(8)->letters()->symbols()->numbers()->mixedCase()],
        ];

        $attributes = [
            'password' => trans('public.password'),
            'password_confirmation' => trans('public.confirm_password'),
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($attributes);

        $validator->validate();

        $dial_code = $request->dial_code;
        $phone = $request->phone_number;

        // Remove leading '+' from dial code if present
        $dial_code = ltrim($dial_code, '+');

        // Remove leading '+' from phone number if present
        $phone = ltrim($phone, '+');

        // Check if phone number already starts with dial code
        if (!str_starts_with($phone, $dial_code)) {
            // Concatenate dial code and phone number
            $phone = '+' . $dial_code . $phone;
        } else {
            // If phone number already starts with dial code, use the phone number directly
            $phone = '+' . $phone;
        }

        $userData = [
            'name' => $request->full_name,
            'email' => $request->email,
            'dial_code' => $request->dial_code,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ];

        if ($request->referral_code) {
            $referral_code = $request->referral_code;
            $check_referral_code = User::where('referral_code', $referral_code)->first();

            if ($check_referral_code) {
                $upline_id = $check_referral_code->id;
                $hierarchyList = empty($check_referral_code['hierarchyList']) ? "-" . $upline_id . "-" : $check_referral_code['hierarchyList'] . $upline_id . "-";

                $userData['upline_id'] = $upline_id;
                $userData['hierarchyList'] = $hierarchyList;
            } else {
                return redirect()->route('login')
                ->with('title', trans('public.invalid_referral_code'))
                ->with('warning', trans('public.invalid_referral_code_desc'));
            }
        }

        $user = User::create($userData);

        // create ct id to link ctrader account
        $ctUser = (new CTraderService)->CreateCTID($user->email);
        $user->ct_user_id = $ctUser['userId'];
        $user->save();

        $user->setReferralId();
        $user->setIdNumber();

        Wallet::create([
            'user_id' => $user->id,
            'name' => 'Cash Wallet',
            'type' => 'cash_wallet',
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'name' => 'Commission Wallet',
            'type' => 'commission_wallet',
        ]);

        event(new Registered($user));

        return redirect()->route('login')
            ->with('title', trans('public.success_registration'))
            ->with('success', trans('public.successfully_registration'));
    }

    public function getDialCodes(Request $request)
    {
        $locale = app()->getLocale();

        $countries = Country::query()
            ->when($request->filled('query'), function ($query) use ($request) {
                $search = $request->input('query');
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_code', 'like', "%{$search}%")
                        ->orWhere('translations', 'like', "%{$search}%");
                });
            })
            ->select('id', 'name', 'phone_code', 'translations')
            ->get()
            ->map(function ($country) use ($locale) {
                $translations = json_decode($country->translations, true);
                $label = $translations[$locale] ?? $country->name;
                return [
                    'phone_code' => $country->phone_code,
                    'name' => $label,
                ];
            });

        return response()->json($countries);
    }
}
