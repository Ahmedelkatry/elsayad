<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required_without:email', 'string'],
            'email' => ['required_without:phone', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = $this->input('phone') ?? $this->input('email');
        $password = $this->input('password');

        // Check if it's an employee (stored in employees table) logging in with mobile
        $employee = \App\Models\employees::where('mobile', $loginInput)->first();
        if ($employee && $employee->phone_password && \Illuminate\Support\Facades\Hash::check($password, $employee->phone_password)) {
            // Employee authenticated! Set employee session variables
            session(['emp_logged_in' => true]);
            session(['emp_id' => $employee->id]);
            session(['emp_name' => $employee->name]);
            session(['emp_mobile' => $employee->mobile]);
            session(['emp_num' => $employee->num]);

            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Check if it's a User logging in with email OR phone (users.phone) OR employee mobile
        $user = \App\Models\User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->orWhere('num', $loginInput)
            ->orWhereHas('employee', function($q) use ($loginInput) {
                $q->where('mobile', $loginInput);
            })->first();



        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Fallback to standard Laravel attempt
        $credentials = ['password' => $password];
        if ($this->has('phone')) {
            $credentials['phone'] = $this->input('phone');
        } else {
            $credentials['email'] = $this->input('email');
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone' => "كلمه المرور أو البريد الإلكتروني أو رقم الهاتف غير صحيح",
                'email' => "كلمه المرور أو البريد الإلكتروني أو رقم الهاتف غير صحيح",
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
