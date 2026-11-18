<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of students with optional search filter.
     */
    public function index(Request $request): View
    {
        $query = Student::withCount('results');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('roll', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(8)->withQueryString();

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student (Protected by auth).
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage (Protected by auth).
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = Student::create($request->validated());

        return redirect()->route('students.show', $student)
            ->with('success', "Student '{$student->name}' (Roll: {$student->roll}) was registered successfully!");
    }

    /**
     * Display the specified student and their calculated results page.
     */
    public function show(Student $student): View
    {
        $student->load(['results' => function ($q) {
            $q->orderBy('course_code', 'asc');
        }]);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student (Protected by auth).
     */
    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage (Protected by auth).
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()->route('students.show', $student)
            ->with('success', "Student details for '{$student->name}' updated successfully!");
    }

    /**
     * Remove the specified student from storage (Protected by auth).
     */
    public function destroy(Student $student): RedirectResponse
    {
        $name = $student->name;
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', "Student '{$name}' and all associated results were deleted.");
    }
}
