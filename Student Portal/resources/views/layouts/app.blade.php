<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Student Result Portal') - Apex University</title>

  <!-- Modern Clean Styling -->
  <style>
    :root {
      --primary: #1e40af;
      --primary-hover: #1d4ed8;
      --secondary: #475569;
      --success: #15803d;
      --danger: #b91c1c;
      --warning: #b45309;
      --bg-body: #f8fafc;
      --bg-card: #ffffff;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --radius: 8px;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background-color: var(--bg-body);
      color: var(--text-main);
      line-height: 1.5;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Container */
    .container {
      width: 100%;
      max-width: 1140px;
      margin-inline: auto;
      padding-inline: 1rem;
    }

    /* Navbar */
    .navbar {
      background: #0f172a;
      color: #ffffff;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 40;
    }

    .navbar-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-block: 0.85rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      text-decoration: none;
      color: #ffffff;
      font-weight: 700;
      font-size: 1.25rem;
    }

    .nav-brand span.highlight {
      color: #60a5fa;
    }

    .nav-links {
      display: flex;
      align-items: center;
      list-style: none;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .nav-links a {
      color: #cbd5e1;
      text-decoration: none;
      font-size: 0.9375rem;
      font-weight: 500;
      padding: 0.45rem 0.75rem;
      border-radius: var(--radius);
      transition: all 0.15s ease;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: #ffffff;
      background-color: #1e293b;
    }

    .user-pill {
      background-color: #1e293b;
      padding: 0.35rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.8125rem;
      color: #93c5fd;
      border: 1px solid #334155;
      display: flex;
      align-items: center;
      gap: 0.35rem;
    }

    /* Main Container */
    main.main-content {
      flex: 1;
      padding-block: 2rem;
    }

    /* Flash Alerts */
    .alert {
      padding: 0.875rem 1.25rem;
      border-radius: var(--radius);
      margin-bottom: 1.5rem;
      font-size: 0.9375rem;
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
    }

    .alert-success {
      background-color: #f0fdf4;
      color: var(--success);
      border: 1px solid #bbf7d0;
    }

    .alert-danger {
      background-color: #fef2f2;
      color: var(--danger);
      border: 1px solid #fca5a5;
    }

    .alert-warning {
      background-color: #fffbeb;
      color: var(--warning);
      border: 1px solid #fde68a;
    }

    .alert ul {
      margin-left: 1.25rem;
      margin-top: 0.35rem;
    }

    /* Cards */
    .card {
      background-color: var(--bg-card);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      border-bottom: 1px solid var(--border);
      padding-bottom: 1rem;
      margin-bottom: 1.25rem;
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text-main);
    }

    .card-subtitle {
      font-size: 0.875rem;
      color: var(--text-muted);
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      padding: 0.55rem 1rem;
      font-size: 0.875rem;
      font-weight: 600;
      border-radius: var(--radius);
      border: 1px solid transparent;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s ease;
    }

    .btn-sm {
      padding: 0.35rem 0.65rem;
      font-size: 0.8125rem;
    }

    .btn-primary {
      background-color: var(--primary);
      color: #ffffff;
      border-color: var(--primary);
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
    }

    .btn-secondary {
      background-color: #ffffff;
      color: var(--secondary);
      border-color: var(--border);
    }

    .btn-secondary:hover {
      background-color: #f1f5f9;
      color: var(--text-main);
    }

    .btn-danger {
      background-color: var(--danger);
      color: #ffffff;
      border-color: var(--danger);
    }

    .btn-danger:hover {
      background-color: #991b1b;
    }

    .btn-outline-danger {
      background: transparent;
      color: var(--danger);
      border-color: #fca5a5;
    }

    .btn-outline-danger:hover {
      background-color: #fef2f2;
      border-color: var(--danger);
    }

    .btn-logout {
      background: none;
      border: none;
      color: #fca5a5;
      font-size: 0.875rem;
      font-weight: 600;
      cursor: pointer;
      padding: 0.45rem 0.75rem;
      border-radius: var(--radius);
      transition: background-color 0.15s ease;
    }

    .btn-logout:hover {
      background-color: rgba(239, 68, 68, 0.15);
      color: #ffffff;
    }

    /* Forms */
    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 0.35rem;
    }

    .form-control {
      width: 100%;
      padding: 0.65rem 0.85rem;
      font-size: 0.9375rem;
      font-family: inherit;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background-color: #ffffff;
      color: var(--text-main);
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.15);
    }

    .form-control.is-invalid {
      border-color: var(--danger);
      background-color: #fef2f2;
    }

    .invalid-feedback {
      color: var(--danger);
      font-size: 0.8125rem;
      font-weight: 500;
      margin-top: 0.3rem;
      display: block;
    }

    .form-hint {
      font-size: 0.8125rem;
      color: var(--text-muted);
      margin-top: 0.25rem;
      display: block;
    }

    /* Tables */
    .table-responsive {
      width: 100%;
      overflow-x: auto;
      border: 1px solid var(--border);
      border-radius: var(--radius);
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.875rem;
    }

    .table th {
      background-color: #f8fafc;
      padding: 0.75rem 1rem;
      font-weight: 600;
      color: #334155;
      border-bottom: 2px solid var(--border);
      white-space: nowrap;
    }

    .table td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }

    .table tbody tr:hover {
      background-color: #f8fafc;
    }

    .table tbody tr:last-child td {
      border-bottom: none;
    }

    /* Badges */
    .badge {
      display: inline-block;
      padding: 0.2rem 0.55rem;
      font-size: 0.75rem;
      font-weight: 700;
      border-radius: 9999px;
      white-space: nowrap;
    }

    .badge-grade-aplus { background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .badge-grade-a     { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
    .badge-grade-aminus{ background-color: #e0f2fe; color: #0284c7; border: 1px solid #7dd3fc; }
    .badge-grade-bplus { background-color: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }
    .badge-grade-b     { background-color: #ede9fe; color: #6d28d9; border: 1px solid #c4b5fd; }
    .badge-grade-bminus{ background-color: #fae8ff; color: #a21caf; border: 1px solid #f0abfc; }
    .badge-grade-cplus { background-color: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
    .badge-grade-c     { background-color: #fef3c7; color: #b45309; border: 1px solid #fcd34d; }
    .badge-grade-d     { background-color: #ffedd5; color: #c2410c; border: 1px solid #fdba74; }
    .badge-grade-f     { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .badge-dept {
      background-color: #f1f5f9;
      color: #334155;
      border: 1px solid #cbd5e1;
    }

    /* Summary Stat Cards Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-card {
      background: #ffffff;
      padding: 1.25rem;
      border-radius: var(--radius);
      border: 1px solid var(--border);
      border-left: 4px solid var(--primary);
    }

    .stat-label {
      font-size: 0.8125rem;
      color: var(--text-muted);
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 0.03em;
    }

    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text-main);
      margin-top: 0.25rem;
    }

    /* Footer */
    .site-footer {
      background-color: #0f172a;
      color: #94a3b8;
      padding-block: 1.5rem;
      border-top: 1px solid #1e293b;
      margin-top: auto;
      font-size: 0.875rem;
      text-align: center;
    }

    .site-footer a {
      color: #60a5fa;
      text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .navbar-inner {
        flex-direction: column;
        align-items: flex-start;
      }
      .nav-links {
        width: 100%;
        justify-content: flex-start;
      }
    }
  </style>
  @yield('styles')
</head>
<body>

  <!-- Common Navigation Bar -->
  <header class="navbar">
    <div class="container navbar-inner">
      <a href="{{ route('students.index') }}" class="nav-brand">
        🎓 Apex <span class="highlight">Result Portal</span>
      </a>

      <nav aria-label="Portal Navigation">
        <ul class="nav-links">
          <li>
            <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.index') ? 'active' : '' }}">
              📋 Students & Results
            </a>
          </li>

          @auth
            <li>
              <a href="{{ route('students.create') }}" class="{{ request()->routeIs('students.create') ? 'active' : '' }}">
                ➕ Add Student
              </a>
            </li>
            <li>
              <div class="user-pill">
                <span>👤 {{ Auth::user()->name }}</span>
              </div>
            </li>
            <li>
              <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout" title="Sign out of portal">Log Out</button>
              </form>
            </li>
          @else
            <li>
              <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
                🔐 Teacher Login
              </a>
            </li>
            <li>
              <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">
                ✍️ Register
              </a>
            </li>
          @endauth
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content Landmark -->
  <main class="main-content">
    <div class="container">

      <!-- Success Flash Alert -->
      @if (session('success'))
        <div class="alert alert-success" role="alert">
          <span style="font-size: 1.25rem;">✅</span>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      <!-- Error Flash Alert -->
      @if (session('error'))
        <div class="alert alert-danger" role="alert">
          <span style="font-size: 1.25rem;">❌</span>
          <div>{{ session('error') }}</div>
        </div>
      @endif

      <!-- Global Form Validation Alert -->
      @if ($errors->any())
        <div class="alert alert-danger" role="alert">
          <span style="font-size: 1.25rem;">⚠️</span>
          <div>
            <strong>Please resolve the following input issues:</strong>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      @endif

      <!-- Page Specific Content -->
      @yield('content')

    </div>
  </main>

  <!-- Common Footer Landmark -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; {{ date('Y') }} Apex University Student Result Portal. Laravel Framework Academic Application.</p>
      <p style="margin-top: 0.35rem; font-size: 0.8125rem; color: #64748b;">
        Protected routes authenticated with Laravel Auth Middleware • Automated Grade Computation
      </p>
    </div>
  </footer>

  @yield('scripts')
</body>
</html>
