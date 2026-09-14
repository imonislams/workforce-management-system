<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $remember = $request->boolean('remember');
        $password = $request->input('password');
        $loginType = $request->input('login_type');

        // Automatically determine if login attempt is for Admin (email present or login_type admin)
        if ($request->filled('email') || $loginType === 'admin' || ! $request->filled('employee_code')) {
            $email = trim($request->input('email'));
            $user = User::where('email', $email)->where('role', 'admin')->first();

            if (! $user || ! Hash::check($password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            if (! $user->isActive()) {
                throw ValidationException::withMessages([
                    'email' => __('Your account has been deactivated.'),
                ]);
            }

            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Employee login attempt
        $code = trim($request->input('employee_code'));
        $employee = Employee::where('employee_code', $code)->first();

        if (! $employee || ! $employee->user) {
            throw ValidationException::withMessages([
                'employee_code' => __('Invalid employee code or account credentials.'),
            ]);
        }

        $user = $employee->user;

        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'employee_code' => __('auth.failed'),
            ]);
        }

        if (! $user->isActive() || $employee->employment_status !== 'active') {
            throw ValidationException::withMessages([
                'employee_code' => __('Your employee account is inactive.'),
            ]);
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('employee.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
