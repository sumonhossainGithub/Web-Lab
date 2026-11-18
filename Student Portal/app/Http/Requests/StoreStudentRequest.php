<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'roll' => 'required|string|max:50|unique:students,roll',
            'department' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:students,email',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'roll.required' => 'Roll number is required.',
            'roll.unique' => 'This roll number is already registered for another student.',
            'department.required' => 'Please choose or enter a department.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
        ];
    }
}
