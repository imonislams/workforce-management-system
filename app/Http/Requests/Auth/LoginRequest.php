<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login_type' => ['nullable', 'string', 'in:admin,employee'],
            'email' => ['required_if:login_type,admin', 'nullable', 'email'],
            'employee_code' => ['required_if:login_type,employee', 'nullable', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }
}
