<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_code' => 'required|string|max:50',
            'marks' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'course_code.required' => 'Course code is required (e.g. CSE-101).',
            'marks.required' => 'Marks are required.',
            'marks.numeric' => 'Marks must be a valid number between 0 and 100.',
            'marks.min' => 'Marks cannot be less than 0.',
            'marks.max' => 'Marks cannot exceed 100.',
        ];
    }
}
