<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\BdPhone;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $emailRules = ['nullable', 'email', 'max:255'];
        
        if (! auth()->check()) {
            $emailRules[] = 'required';
            $emailRules[] = Rule::unique('users', 'email')->whereNull('deleted_at');
        }

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => $emailRules,
            'phone' => ['required', 'string', new BdPhone()],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'notes' => ['nullable', 'string', 'max:500'],
            'save_address' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('An account with this email already exists. Please <a href=":url" class="underline font-bold text-primary-600">log in</a> to continue.', ['url' => route('login')]),
        ];
    }
}
