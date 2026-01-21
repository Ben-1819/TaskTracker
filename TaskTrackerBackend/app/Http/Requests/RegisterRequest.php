<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorise the user to continue
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Define the validation rules for the fields being sent when a user attempts to register
        return [
            'first_name' => ['required', 'string', 'max:55'],
            'last_name' => ['required', 'string', 'max:55'],
            'email' => ['required', 'email', 'unique:users', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Define the error messages if validation rules are not met
     * @return array<string, string>
     */
    public function messages(): array
    {
        // Create an array of custom error messages
        $messages = [
            'first_name.required' => 'First name is a required field',
            'first_name.string' => 'First name must be of data type string',
            'first_name.max' => 'First name must not exceed 55 characters',
            'last_name.required' => 'Last name is a required field',
            'last_name.string' => 'Last name must be of data type string',
            'last_name.max' => 'Last name must not exceed 55 characters',
            'email.required' => 'Email is a required field',
            'email.email' => 'The email entered must be a valid email',
            'email.unique' => 'This email already has an account connected to it',
            'email.max' => 'Email must not exceed 100 characters',
            'password.required' => 'Password is a required field',
            'password.string' => 'Password must be of data type string',
            'password.min' => 'Password must be at least 6 characters'
        ];

        // Return the array
        return $messages;
    }
}
