@extends('layouts.app')

@section('title', 'Faculty & Staff Registration')

@section('content')
<div style="max-width: 480px; margin-inline: auto;">
  <div class="card">
    <div class="card-header" style="justify-content: center; text-align: center;">
      <div>
        <h1 class="card-title" style="font-size: 1.35rem;">✍️ Faculty & Staff Account Sign-up</h1>
        <p class="card-subtitle">Create authorized credentials to manage students and results.</p>
      </div>
    </div>

    <form action="{{ route('register') }}" method="POST" novalidate>
      @csrf

      <!-- Name -->
      <div class="form-group">
        <label for="name" class="form-label">Full Name <span style="color: var(--danger);">*</span></label>
        <input 
          type="text" 
          id="name" 
          name="name" 
          value="{{ old('name') }}" 
          class="form-control @error('name') is-invalid @enderror" 
          placeholder="Professor Jane Doe" 
          required 
          autocomplete="name" 
          autofocus
        >
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Email -->
      <div class="form-group">
        <label for="email" class="form-label">Email Address <span style="color: var(--danger);">*</span></label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          value="{{ old('email') }}" 
          class="form-control @error('email') is-invalid @enderror" 
          placeholder="faculty@apex.edu" 
          required 
          autocomplete="email"
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password" class="form-label">Password (Min 8 Characters) <span style="color: var(--danger);">*</span></label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="form-control @error('password') is-invalid @enderror" 
          required 
          autocomplete="new-password"
        >
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password <span style="color: var(--danger);">*</span></label>
        <input 
          type="password" 
          id="password_confirmation" 
          name="password_confirmation" 
          class="form-control" 
          required 
          autocomplete="new-password"
        >
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">
        Register & Enter Portal
      </button>

      <div style="text-align: center; margin-top: 1.25rem; font-size: 0.875rem; color: var(--text-muted);">
        Already registered? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">Log In</a>
      </div>
    </form>
  </div>
</div>
@endsection
