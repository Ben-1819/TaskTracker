<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
        // Create custom validation rules for the input fields
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'date_due' => ['required', 'date', 'after:today']
        ];
    }

    /**
     * Custom error messages for when the validation rules are broken
     * @return array{date_due.after: string, date_due.date: string, date_due.required: string, description.max: string, description.required: string, description.string: string, name.max: string, name.required: string, name.string: string}
     */
    public function messages(): array
    {
        // Create an array of custom error messages
        $messages = [
            'name.required' => 'Name is a required field',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must be 100 characters or less',
            'description.required' => 'Description is a required field',
            'description.string' => 'Description must be a string',
            'description.max' => 'Description must be 255 characters or less',
            'category.required' => 'Category is a required field',
            'category.string' => 'Category must be of data type string',
            'date_due.required' => 'Date due is a required field',
            'date_due.date' => 'Date due must be a valid date',
            'date_due.after' => 'Date due must be a date in the future'
        ];

        return $messages;
    }
}
