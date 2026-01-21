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
}
