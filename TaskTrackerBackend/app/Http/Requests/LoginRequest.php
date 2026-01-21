<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorize the user to continue
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Define the validation rules to be used when a user tries to login
        return [
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:6']
        ];
    }

    /**
     * Create custom error messages for the validation rules
     * @return array{string, string}
     */
    public function messages(): array
    {
        // Create an array of custom error messages
        $messages = [
            'email.required' => 'Email is a required field',
            'email.string' => 'Email must be of data type string',
            'email.email' => 'The email you enter must be a valid email',
            'email.max' => 'Email can\'t exceed 100 characters',
            'password.required' => 'Password is a required field',
            'password.string' => 'Password must be of data type string',
            'password.min' => 'Password must be at least 6 characters'
        ];

        // Return the error messages
        return $messages;
    }
}
