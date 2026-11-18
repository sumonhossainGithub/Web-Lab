@extends('layouts.app')

@section('title', 'Students Directory & Results')

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h1 class="card-title">Student Directory & Academic Records</h1>
      <p class="card-subtitle">Browse student profiles, search by Roll number, or view calculated results.</p>
    </div>

    <div>
      @auth
        <a href="{{ route('students.create') }}" class="btn btn-primary">
          ➕ Register New Student
        </a>
      @else
        <a href="{{ route('login') }}" class="btn btn-secondary" title="Login required to manage records">
          🔐 Log In to Add / Edit Students
        </a>
      @endauth
    </div>
  </div>

  <!-- Search Filter Form -->
  <form action="{{ route('students.index') }}" method="GET" style="margin-bottom: 1.5rem;">
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
      <input 
        type="search" 
        name="search" 
        value="{{ request('search') }}" 
        class="form-control" 
        placeholder="Search by student name, roll number, or department..." 
        style="flex: 1; min-width: 250px;"
      >
      <button type="submit" class="btn btn-primary">
        🔍 Search
      </button>
      @if (request('search'))
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
          Reset
        </a>
      @endif
    </div>
  </form>

  <!-- Students Table -->
  @if ($students->count() > 0)
    <div class="table-responsive">
      <table class="table" aria-label="Student records list">
        <thead>
          <tr>
            <th scope="col">Roll Number</th>
            <th scope="col">Student Name</th>
            <th scope="col">Department</th>
            <th scope="col">Email</th>
            <th scope="col" style="text-align: center;">Courses Taken</th>
            <th scope="col" style="text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($students as $student)
            <tr>
              <td>
                <strong style="color: var(--primary);">{{ $student->roll }}</strong>
              </td>
              <td>
                <strong>{{ $student->name }}</strong>
              </td>
              <td>
                <span class="badge badge-dept">{{ $student->department }}</span>
              </td>
              <td>
                <a href="mailto:{{ $student->email }}" style="color: var(--secondary); text-decoration: none;">
                  {{ $student->email }}
                </a>
              </td>
              <td style="text-align: center;">
                <span class="badge" style="background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                  {{ $student->results_count }} Course{{ $student->results_count === 1 ? '' : 's' }}
                </span>
              </td>
              <td style="text-align: right; white-space: nowrap;">
                <!-- View Results & Grade Page (Publicly Viewable) -->
                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-primary" title="View Result Sheet">
                  📊 View Results
                </a>

                @auth
                  <!-- Edit Student (Protected) -->
                  <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-secondary" title="Edit Student Profile">
                    ✏️ Edit
                  </a>

                  <!-- Delete Student (Protected) -->
                  <form action="{{ route('students.destroy', $student) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete {{ $student->name }} and all their results?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Student">
                      🗑️
                    </button>
                  </form>
                @endauth
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.25rem;">
      {{ $students->links() }}
    </div>
  @else
    <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted); background: #f8fafc; border-radius: var(--radius);">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📂</div>
      <h3>No Student Records Found</h3>
      <p style="margin-top: 0.25rem;">
        @if (request('search'))
          No students matched your search criteria "{{ request('search') }}". <a href="{{ route('students.index') }}">Clear Search</a>
        @else
          No students have been registered yet.
          @auth
            <a href="{{ route('students.create') }}">Add the first student now</a>.
          @else
            <a href="{{ route('login') }}">Log in</a> to begin adding students.
          @endauth
        @endif
      </p>
    </div>
  @endif
</div>
@endsection
