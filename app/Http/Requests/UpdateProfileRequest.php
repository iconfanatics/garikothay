<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\BdPhone;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('phone')) {
            $this->merge([
                'phone' => preg_replace('/^(?:\+?88|0088)?(01[3-9]\d{8}|096\d{8})$/', '$1', $this->input('phone')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
            'phone' => ['nullable', 'string', new BdPhone(), Rule::unique('users', 'phone')->ignore(auth()->id())],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'locale' => ['nullable', 'string', Rule::in(['bn', 'en'])],
        ];
    }
}
