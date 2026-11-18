<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentParam = $this->route('student');
        $studentId = is_object($studentParam) ? $studentParam->id : $studentParam;

        return [
            'name' => 'required|string|min:2|max:255',
            'roll' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'roll')->ignore($studentId),
            ],
            'department' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($studentId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'roll.required' => 'Roll number is required.',
            'roll.unique' => 'This roll number is already in use by another student.',
            'department.required' => 'Department is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email format.',
            'email.unique' => 'This email address is already assigned to another student.',
        ];
    }
}
