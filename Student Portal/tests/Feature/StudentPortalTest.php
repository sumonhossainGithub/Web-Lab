<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test public student listing page.
     */
    public function test_students_index_is_accessible_publicly(): void
    {
        $response = $this->get('/students');
        $response->assertStatus(200);
        $response->assertSee('Student Directory');
        $response->assertSee('Sarah Jenkins');
    }

    /**
     * Test student results page with automatic grade calculation.
     */
    public function test_student_results_page_displays_results_and_grades(): void
    {
        $student = Student::first();
        $response = $this->get("/students/{$student->id}");

        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee($student->roll);
        $response->assertSee('Academic Results Sheet');
        $response->assertSee('Cumulative GPA');
    }

    /**
     * Test that create, edit, and delete routes are protected by auth middleware.
     */
    public function test_protected_routes_redirect_unauthenticated_users(): void
    {
        $student = Student::first();

        // Create
        $this->get('/students/create')->assertRedirect('/login');
        $this->post('/students', [])->assertRedirect('/login');

        // Edit
        $this->get("/students/{$student->id}/edit")->assertRedirect('/login');
        $this->put("/students/{$student->id}", [])->assertRedirect('/login');

        // Delete
        $this->delete("/students/{$student->id}")->assertRedirect('/login');
    }

    /**
     * Test authenticated user can create student with validation.
     */
    public function test_authenticated_user_can_create_student(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->post('/students', [
            'name' => 'David Warner',
            'roll' => 'CSE-2026-999',
            'department' => 'Computer Science & Engineering',
            'email' => 'david.w@portal.edu',
        ]);

        $student = Student::where('roll', 'CSE-2026-999')->first();
        $this->assertNotNull($student);
        $response->assertRedirect("/students/{$student->id}");
    }

    /**
     * Test form validation rejects invalid inputs.
     */
    public function test_form_validation_rejects_missing_and_duplicate_fields(): void
    {
        $user = User::first();
        $existing = Student::first();

        $response = $this->actingAs($user)->post('/students', [
            'name' => '',
            'roll' => $existing->roll, // Duplicate roll
            'department' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['name', 'roll', 'department', 'email']);
    }

    /**
     * Test result creation automatically calculates the correct grade.
     */
    public function test_course_result_automatically_calculates_letter_grade(): void
    {
        $user = User::first();
        $student = Student::first();

        // 85 marks should automatically calculate to A+
        $this->actingAs($user)->post("/students/{$student->id}/results", [
            'course_code' => 'AI-401',
            'marks' => 85.0,
        ]);

        $result = Result::where('student_id', $student->id)
            ->where('course_code', 'AI-401')
            ->first();

        $this->assertNotNull($result);
        $this->assertEquals('A+', $result->grade);

        // 62 marks should automatically calculate to B
        $this->actingAs($user)->post("/students/{$student->id}/results", [
            'course_code' => 'MATH-301',
            'marks' => 62.0,
        ]);

        $result2 = Result::where('student_id', $student->id)
            ->where('course_code', 'MATH-301')
            ->first();

        $this->assertEquals('B', $result2->grade);
    }
}
