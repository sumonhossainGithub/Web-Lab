<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResultRequest;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;

class ResultController extends Controller
{
    /**
     * Store a newly created course result with automated grade computation.
     */
    public function store(StoreResultRequest $request, Student $student): RedirectResponse
    {
        $marks = (float) $request->validated('marks');
        $calculatedGrade = Result::calculateGrade($marks);

        $student->results()->create([
            'course_code' => strtoupper(trim($request->validated('course_code'))),
            'marks' => $marks,
            'grade' => $calculatedGrade,
        ]);

        return redirect()->route('students.show', $student)
            ->with('success', "Result for '{$request->validated('course_code')}' added with Grade: {$calculatedGrade} (Marks: {$marks})!");
    }

    /**
     * Remove the specified course result.
     */
    public function destroy(Student $student, Result $result): RedirectResponse
    {
        $code = $result->course_code;
        $result->delete();

        return redirect()->route('students.show', $student)
            ->with('success', "Result for course '{$code}' deleted.");
    }
}
