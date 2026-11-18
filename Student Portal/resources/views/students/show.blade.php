@extends('layouts.app')

@section('title', $student->name . ' - Academic Results & Transcript')

@section('content')
<!-- Student Profile Card -->
<div class="card">
  <div class="card-header">
    <div>
      <span class="badge badge-dept" style="margin-bottom: 0.35rem;">{{ $student->department }}</span>
      <h1 class="card-title" style="font-size: 1.5rem;">{{ $student->name }}</h1>
      <p class="card-subtitle">
        Roll No: <strong>{{ $student->roll }}</strong> • Email: <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
      </p>
    </div>

    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
      <a href="{{ route('students.index') }}" class="btn btn-secondary">
        &larr; All Students
      </a>

      @auth
        <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
          ✏️ Edit Profile
        </a>
        <form action="{{ route('students.destroy', $student) }}" method="POST" style="display: inline;" onsubmit="return confirm('Permanently delete student {{ $student->name }}?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">
            🗑️ Delete Student
          </button>
        </form>
      @endauth
    </div>
  </div>

  <!-- Performance Summary Statistics -->
  <div class="stats-grid">
    <div class="stat-card" style="border-left-color: #2563eb;">
      <div class="stat-label">Cumulative GPA</div>
      <div class="stat-value" style="color: #1e40af;">
        {{ number_format($student->gpa, 2) }} <span style="font-size: 0.9rem; color: #64748b;">/ 4.00</span>
      </div>
    </div>

    <div class="stat-card" style="border-left-color: #059669;">
      <div class="stat-label">Average Marks</div>
      <div class="stat-value" style="color: #047857;">
        {{ number_format($student->average_marks, 2) }}%
      </div>
    </div>

    <div class="stat-card" style="border-left-color: #7c3aed;">
      <div class="stat-label">Enrolled Courses</div>
      <div class="stat-value" style="color: #6d28d9;">
        {{ $student->results->count() }}
      </div>
    </div>

    <div class="stat-card" style="border-left-color: #d97706;">
      <div class="stat-label">Academic Status</div>
      <div class="stat-value" style="font-size: 1.25rem; color: #b45309; padding-top: 0.25rem;">
        @if ($student->results->isEmpty())
          <span style="color: #64748b;">No Grades Yet</span>
        @elseif ($student->gpa >= 3.75)
          🌟 High Honors
        @elseif ($student->gpa >= 3.00)
          ✅ Good Standing
        @elseif ($student->gpa >= 2.00)
          ⚠️ Satisfactory
        @else
          ❌ Academic Probation
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Layout for Results Table & Add Result Form -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; align-items: start;">

  <!-- Column 1: Academic Results Table -->
  <div class="card" style="grid-column: span 2;">
    <div class="card-header">
      <div>
        <h2 class="card-title">Academic Results Sheet</h2>
        <p class="card-subtitle">Automated letter grade calculation based on course marks.</p>
      </div>
      <div>
        <span class="badge" style="background-color: #f1f5f9; color: #334155; padding: 0.35rem 0.75rem;">
          Total: {{ $student->results->count() }} Record{{ $student->results->count() === 1 ? '' : 's' }}
        </span>
      </div>
    </div>

    @if ($student->results->count() > 0)
      <div class="table-responsive">
        <table class="table" aria-label="Course results table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Course Code</th>
              <th scope="col" style="text-align: center;">Marks (100)</th>
              <th scope="col" style="text-align: center;">Calculated Grade</th>
              <th scope="col" style="text-align: center;">Grade Point</th>
              @auth
                <th scope="col" style="text-align: right;">Action</th>
              @endauth
            </tr>
          </thead>
          <tbody>
            @foreach ($student->results as $index => $result)
              @php
                $grade = $result->grade;
                $badgeClass = 'badge-grade-f';
                if ($grade === 'A+') $badgeClass = 'badge-grade-aplus';
                elseif ($grade === 'A') $badgeClass = 'badge-grade-a';
                elseif ($grade === 'A-') $badgeClass = 'badge-grade-aminus';
                elseif ($grade === 'B+') $badgeClass = 'badge-grade-bplus';
                elseif ($grade === 'B') $badgeClass = 'badge-grade-b';
                elseif ($grade === 'B-') $badgeClass = 'badge-grade-bminus';
                elseif ($grade === 'C+') $badgeClass = 'badge-grade-cplus';
                elseif ($grade === 'C') $badgeClass = 'badge-grade-c';
                elseif ($grade === 'D') $badgeClass = 'badge-grade-d';

                $gradePoint = \App\Models\Result::calculateGradePoint((float) $result->marks);
              @endphp
              <tr>
                <td><strong>{{ $index + 1 }}</strong></td>
                <td>
                  <strong style="color: #1e40af; font-size: 0.95rem;">{{ $result->course_code }}</strong>
                </td>
                <td style="text-align: center; font-weight: 600;">
                  {{ number_format($result->marks, 1) }}
                </td>
                <td style="text-align: center;">
                  <span class="badge {{ $badgeClass }}" style="font-size: 0.85rem; padding: 0.3rem 0.65rem;">
                    {{ $result->grade }}
                  </span>
                </td>
                <td style="text-align: center; font-weight: 600; color: #475569;">
                  {{ number_format($gradePoint, 2) }}
                </td>
                @auth
                  <td style="text-align: right;">
                    <form action="{{ route('results.destroy', [$student, $result]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove course result for {{ $result->course_code }}?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Course Result">
                        🗑️ Remove
                      </button>
                    </form>
                  </td>
                @endauth
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr style="background-color: #f8fafc; font-weight: 700; border-top: 2px solid var(--border);">
              <td colspan="2">Overall Average & Standing</td>
              <td style="text-align: center; color: #047857;">{{ number_format($student->average_marks, 1) }}%</td>
              <td style="text-align: center;">
                <span class="badge badge-grade-aplus" style="padding: 0.35rem 0.65rem;">
                  {{ \App\Models\Result::calculateGrade($student->average_marks) }}
                </span>
              </td>
              <td style="text-align: center; color: #1e40af;">GPA: {{ number_format($student->gpa, 2) }}</td>
              @auth <td></td> @endauth
            </tr>
          </tfoot>
        </table>
      </div>
    @else
      <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted); background: #f8fafc; border-radius: var(--radius);">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
        <h3>No Examination Results Yet</h3>
        <p style="margin-top: 0.25rem;">
          No courses have been graded for {{ $student->name }} yet.
          @auth
            Use the form to record their first result.
          @else
            <a href="{{ route('login') }}">Log in as faculty</a> to record results.
          @endauth
        </p>
      </div>
    @endif
  </div>

  <!-- Column 2: Add Result Form & Grading Scale -->
  <div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Add Course Result Form -->
    <div class="card">
      <div class="card-header">
        <div>
          <h3 class="card-title" style="font-size: 1.125rem;">➕ Record Course Result</h3>
          <p class="card-subtitle">Grade is calculated automatically upon entry.</p>
        </div>
      </div>

      @auth
        <form action="{{ route('results.store', $student) }}" method="POST" novalidate>
          @csrf

          <!-- Course Code -->
          <div class="form-group">
            <label for="course_code" class="form-label">
              Course Code <span style="color: var(--danger);">*</span>
            </label>
            <input 
              type="text" 
              id="course_code" 
              name="course_code" 
              value="{{ old('course_code') }}" 
              class="form-control @error('course_code') is-invalid @enderror" 
              placeholder="e.g. CSE-311, PHY-102" 
              required
              autocomplete="off"
            >
            @error('course_code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Marks -->
          <div class="form-group">
            <label for="marks" class="form-label">
              Marks Obtained (0 - 100) <span style="color: var(--danger);">*</span>
            </label>
            <input 
              type="number" 
              step="0.01" 
              min="0" 
              max="100" 
              id="marks" 
              name="marks" 
              value="{{ old('marks') }}" 
              class="form-control @error('marks') is-invalid @enderror" 
              placeholder="e.g. 84.5" 
              required
            >
            @error('marks')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Live Automatic Grade Calculation Preview -->
          <div style="background: #f1f5f9; padding: 0.85rem; border-radius: var(--radius); margin-bottom: 1.25rem; border: 1px solid var(--border);">
            <div style="font-size: 0.8125rem; color: var(--text-muted); font-weight: 600;">
              ⚡ Automatic Calculation Preview:
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.35rem;">
              <span>Predicted Grade:</span>
              <strong id="preview-grade" style="font-size: 1.15rem; color: #1e40af;">—</strong>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.2rem; font-size: 0.875rem;">
              <span>Grade Point:</span>
              <span id="preview-point" style="font-weight: 600; color: #475569;">—</span>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            💾 Save & Compute Grade
          </button>
        </form>
      @else
        <div style="text-align: center; padding: 1.5rem 0.5rem; background: #f8fafc; border-radius: var(--radius);">
          <div style="font-size: 1.75rem; margin-bottom: 0.35rem;">🔒</div>
          <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.85rem;">
            Faculty login required to submit or alter examination results.
          </p>
          <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">
            Log In to Grade Student
          </a>
        </div>
      @endauth
    </div>

    <!-- Grading Scale Reference Card -->
    <div class="card" style="background-color: #fafafa;">
      <h3 class="card-title" style="font-size: 1rem; margin-bottom: 0.75rem;">
        📐 University Grading Scale Matrix
      </h3>
      <table style="width: 100%; font-size: 0.8125rem; border-collapse: collapse;">
        <thead>
          <tr style="border-bottom: 1px solid #cbd5e1; text-align: left; color: #475569;">
            <th style="padding: 0.25rem 0;">Marks Range</th>
            <th style="padding: 0.25rem 0;">Grade</th>
            <th style="padding: 0.25rem 0; text-align: right;">Point</th>
          </tr>
        </thead>
        <tbody style="color: #334155;">
          <tr><td>80% - 100%</td><td><span class="badge badge-grade-aplus">A+</span></td><td style="text-align: right;">4.00</td></tr>
          <tr><td>75% - 79%</td><td><span class="badge badge-grade-a">A</span></td><td style="text-align: right;">3.75</td></tr>
          <tr><td>70% - 74%</td><td><span class="badge badge-grade-aminus">A-</span></td><td style="text-align: right;">3.50</td></tr>
          <tr><td>65% - 69%</td><td><span class="badge badge-grade-bplus">B+</span></td><td style="text-align: right;">3.25</td></tr>
          <tr><td>60% - 64%</td><td><span class="badge badge-grade-b">B</span></td><td style="text-align: right;">3.00</td></tr>
          <tr><td>55% - 59%</td><td><span class="badge badge-grade-bminus">B-</span></td><td style="text-align: right;">2.75</td></tr>
          <tr><td>50% - 54%</td><td><span class="badge badge-grade-cplus">C+</span></td><td style="text-align: right;">2.50</td></tr>
          <tr><td>45% - 49%</td><td><span class="badge badge-grade-c">C</span></td><td style="text-align: right;">2.25</td></tr>
          <tr><td>40% - 44%</td><td><span class="badge badge-grade-d">D</span></td><td style="text-align: right;">2.00</td></tr>
          <tr><td>0% - 39%</td><td><span class="badge badge-grade-f">F</span></td><td style="text-align: right;">0.00</td></tr>
        </tbody>
      </table>
    </div>

  </div>

</div>
@endsection

@section('scripts')
<script>
  // Client-side instant preview of grade calculation matching backend logic
  document.addEventListener('DOMContentLoaded', () => {
    const marksInput = document.getElementById('marks');
    const previewGrade = document.getElementById('preview-grade');
    const previewPoint = document.getElementById('preview-point');

    if (marksInput && previewGrade && previewPoint) {
      function updateGradePreview() {
        const val = parseFloat(marksInput.value);
        if (isNaN(val) || val < 0 || val > 100) {
          previewGrade.textContent = '—';
          previewPoint.textContent = '—';
          return;
        }

        let grade = 'F';
        let point = '0.00';

        if (val >= 80) { grade = 'A+'; point = '4.00'; }
        else if (val >= 75) { grade = 'A'; point = '3.75'; }
        else if (val >= 70) { grade = 'A-'; point = '3.50'; }
        else if (val >= 65) { grade = 'B+'; point = '3.25'; }
        else if (val >= 60) { grade = 'B'; point = '3.00'; }
        else if (val >= 55) { grade = 'B-'; point = '2.75'; }
        else if (val >= 50) { grade = 'C+'; point = '2.50'; }
        else if (val >= 45) { grade = 'C'; point = '2.25'; }
        else if (val >= 40) { grade = 'D'; point = '2.00'; }

        previewGrade.textContent = grade;
        previewPoint.textContent = point;
      }

      marksInput.addEventListener('input', updateGradePreview);
      updateGradePreview();
    }
  });
</script>
@endsection
