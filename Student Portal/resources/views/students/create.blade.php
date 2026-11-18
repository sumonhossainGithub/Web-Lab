@extends('layouts.app')

@section('title', 'Register New Student')

@section('content')
<div style="max-width: 680px; margin-inline: auto;">
  <div class="card">
    <div class="card-header">
      <div>
        <h1 class="card-title">Register New Student</h1>
        <p class="card-subtitle">Protected administrative action: Enter student identification details.</p>
      </div>
      <div>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
          &larr; Back to Directory
        </a>
      </div>
    </div>

    <form action="{{ route('students.store') }}" method="POST" novalidate>
      @csrf

      <!-- Full Name -->
      <div class="form-group">
        <label for="name" class="form-label">
          Student Full Name <span style="color: var(--danger);">*</span>
        </label>
        <input 
          type="text" 
          id="name" 
          name="name" 
          value="{{ old('name') }}" 
          class="form-control @error('name') is-invalid @enderror" 
          placeholder="e.g. Alex Johnson" 
          required 
          autocomplete="name"
        >
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @else
          <span class="form-hint">Enter the student's legal name.</span>
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
          value="{{ old('roll') }}" 
          class="form-control @error('roll') is-invalid @enderror" 
          placeholder="e.g. CSE-2026-101" 
          required
        >
        @error('roll')
          <div class="invalid-feedback">{{ $message }}</div>
        @else
          <span class="form-hint">Must be unique across the university database.</span>
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
          <option value="">-- Choose Academic Department --</option>
          <option value="Computer Science & Engineering" {{ old('department') == 'Computer Science & Engineering' ? 'selected' : '' }}>Computer Science & Engineering</option>
          <option value="Electrical & Electronics Engineering" {{ old('department') == 'Electrical & Electronics Engineering' ? 'selected' : '' }}>Electrical & Electronics Engineering</option>
          <option value="Mechanical Engineering" {{ old('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
          <option value="Civil & Environmental Engineering" {{ old('department') == 'Civil & Environmental Engineering' ? 'selected' : '' }}>Civil & Environmental Engineering</option>
          <option value="Business Administration" {{ old('department') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
          <option value="Biotechnology" {{ old('department') == 'Biotechnology' ? 'selected' : '' }}>Biotechnology</option>
          <option value="Data Science & AI" {{ old('department') == 'Data Science & AI' ? 'selected' : '' }}>Data Science & AI</option>
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
          value="{{ old('email') }}" 
          class="form-control @error('email') is-invalid @enderror" 
          placeholder="student@portal.edu" 
          required 
          autocomplete="email"
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @else
          <span class="form-hint">Institutional or primary student email.</span>
        @enderror
      </div>

      <!-- Action Buttons -->
      <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
        <button type="submit" class="btn btn-primary">
          💾 Save Student Record
        </button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
          Cancel
        </a>
      </div>

    </form>
  </div>
</div>
@endsection
