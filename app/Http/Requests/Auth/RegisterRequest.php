<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'string', 'regex:/^01\d{9}$/'],
            'intake' => ['required', 'integer', 'min:1', 'max:999'],
            'shift' => ['required', 'string', 'in:day,evening'],
            'reference_email_1' => [
                'required',
                'string',
                'email',
                'different:email',
                'different:reference_email_2',
                'exists:users,email,status,verified',
            ],
            'reference_email_2' => [
                'required',
                'string',
                'email',
                'different:email',
                'different:reference_email',
                'exists:users,email,status,verified',
            ],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reference_email_1.exists' => 'This reference must be a verified alumni member.',
            'reference_email_1.different' => 'Reference 1 cannot be the same as your own email or Reference 2.',
            'reference_email_2.exists' => 'This reference must be a verified alumni member.',
            'reference_email_2.different' => 'Reference 2 cannot be the same as your own email or Reference 1.',
            'mobile.regex' => 'Mobile number must be a valid Bangladeshi number (e.g., 01XXXXXXXXX).',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'reference_email_1' => 'Reference 1 email',
            'reference_email_2' => 'Reference 2 email',
        ];
    }
}
