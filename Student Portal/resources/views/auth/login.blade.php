@extends('layouts.app')

@section('title', 'Faculty & Admin Login')

@section('content')
<div style="max-width: 480px; margin-inline: auto;">
  <div class="card">
    <div class="card-header" style="justify-content: center; text-align: center;">
      <div>
        <h1 class="card-title" style="font-size: 1.35rem;">🔐 Faculty & Admin Portal Login</h1>
        <p class="card-subtitle">Authenticate to register students and record examination grades.</p>
      </div>
    </div>

    <!-- Quick Credentials Hint for Testing -->
    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius); padding: 0.85rem; margin-bottom: 1.25rem; font-size: 0.8125rem; color: #1e40af;">
      <strong>🔑 Default Seeded Credentials:</strong><br>
      Email: <code>admin@portal.com</code><br>
      Password: <code>password</code>
    </div>

    <form action="{{ route('login') }}" method="POST" novalidate>
      @csrf

      <!-- Email -->
      <div class="form-group">
        <label for="email" class="form-label">Email Address <span style="color: var(--danger);">*</span></label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          value="{{ old('email', 'admin@portal.com') }}" 
          class="form-control @error('email') is-invalid @enderror" 
          required 
          autocomplete="email" 
          autofocus
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password" class="form-label">Password <span style="color: var(--danger);">*</span></label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="form-control @error('password') is-invalid @enderror" 
          required 
          autocomplete="current-password"
        >
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember" style="font-size: 0.875rem; color: var(--text-muted); cursor: pointer;">
          Remember my session on this device
        </label>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">
        Log In to Portal
      </button>

      <div style="text-align: center; margin-top: 1.25rem; font-size: 0.875rem; color: var(--text-muted);">
        Don't have an administrator account? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600;">Register Here</a>
      </div>
    </form>
  </div>
</div>
@endsection
