@extends('layouts.app')

@section('title', 'Edit Student - ' . $student->name)

@section('content')
<div style="max-width: 680px; margin-inline: auto;">
  <div class="card">
    <div class="card-header">
      <div>
        <h1 class="card-title">Edit Student Profile</h1>
        <p class="card-subtitle">Updating record for <strong>{{ $student->name }}</strong> ({{ $student->roll }})</p>
      </div>
      <div>
        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
          &larr; View Student
        </a>
      </div>
    </div>

    <form action="{{ route('students.update', $student) }}" method="POST" novalidate>
      @csrf
      @method('PUT')

      <!-- Full Name -->
      <div class="form-group">
        <label for="name" class="form-label">
          Student Full Name <span style="color: var(--danger);">*</span>
        </label>
        <input 
          type="text" 
          id="name" 
          name="name" 
          value="{{ old('name', $student->name) }}" 
          class="form-control @error('name') is-invalid @enderror" 
          required 
          autocomplete="name"
        >
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Roll Number -->
      <div class="form-group">
        <label for="roll" class="form-label">
          Roll / Registration Number <span style="color: var(--danger);">*</span>
        </label>
        <input 
          type="text" 
          id="roll" 
          name="roll" 
          value="{{ old('roll', $student->roll) }}" 
          class="form-control @error('roll') is-invalid @enderror" 
          required
        >
        @error('roll')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Department -->
      <div class="form-group">
        <label for="department" class="form-label">
          Academic Department <span style="color: var(--danger);">*</span>
        </label>
        <select 
          id="department" 
          name="department" 
          class="form-control @error('department') is-invalid @enderror" 
          required
        >
          @php
            $currentDept = old('department', $student->department);
            $departments = [
              'Computer Science & Engineering',
              'Electrical & Electronics Engineering',
              'Mechanical Engineering',
              'Civil & Environmental Engineering',
              'Business Administration',
              'Biotechnology',
              'Data Science & AI',
            ];
          @endphp
          @foreach ($departments as $dept)
            <option value="{{ $dept }}" {{ $currentDept == $dept ? 'selected' : '' }}>
              {{ $dept }}
            </option>
          @endforeach
        </select>
        @error('department')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Email Address -->
      <div class="form-group">
        <label for="email" class="form-label">
          Student Email Address <span style="color: var(--danger);">*</span>
        </label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          value="{{ old('email', $student->email) }}" 
          class="form-control @error('email') is-invalid @enderror" 
          required 
          autocomplete="email"
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Action Buttons -->
      <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
        <button type="submit" class="btn btn-primary">
          💾 Update Student Information
        </button>
        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
          Cancel
        </a>
      </div>

    </form>
  </div>
</div>
@endsection
