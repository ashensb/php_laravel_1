<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Register</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    .split-wrap {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }

    /* LEFT PANEL */
    .left-panel {
      position: relative;
      flex: 1 1 55%;
      background: linear-gradient(160deg, #0e3b4d 0%, #145c73 35%, #1c7a8f 65%, #2a97a8 100%),
                  url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1400&q=80');
      background-blend-mode: multiply;
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: flex-end;
      padding: 60px;
      color: #ffffff;
    }
    .left-panel::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(6,20,26,0.05) 0%, rgba(6,20,26,0.65) 100%);
    }
    .left-content {
      position: relative;
      z-index: 2;
      max-width: 480px;
    }
    .left-content h2 {
      font-weight: 700;
      font-size: 1.9rem;
      line-height: 1.35;
      text-shadow: 0 2px 12px rgba(0,0,0,0.35);
    }
    .brand-mark {
      position: absolute;
      top: 32px;
      left: 40px;
      z-index: 2;
      font-weight: 700;
      font-size: 1.15rem;
      color: #ffffff;
      letter-spacing: .3px;
    }
    .brand-mark i { color: #7dd3e0; margin-right: 6px; }

    /* RIGHT PANEL */
    .right-panel {
      flex: 1 1 45%;
      background: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      clip-path: ellipse(85% 100% at 100% 50%);
      margin-left: -80px;
      padding: 40px 0;
    }
    @media (max-width: 991px) {
      .right-panel { clip-path: none; margin-left: 0; }
      .split-wrap { flex-direction: column; }
      .left-panel { flex: 0 0 auto; min-height: 200px; padding: 30px; }
    }

    .register-box { width: 100%; max-width: 380px; padding: 20px 30px; }

    .form-control, .form-select {
      border-color: #e3e6ec;
      padding: 10px 14px;
      font-size: .92rem;
    }
    .form-control:focus, .form-select:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 0.2rem rgba(37,99,235,.15);
    }
    .form-label { color: #374151; font-size: .85rem; font-weight: 600; }

    .btn-register {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 11px;
      border-radius: 10px;
      transition: opacity .15s ease;
    }
    .btn-register:hover { opacity: .92; color:#fff; }

    .welcome-title {
      font-weight: 800;
      font-size: 1.9rem;
      color: #111827;
      line-height: 1.15;
      margin-bottom: 4px;
    }
    .welcome-sub { color: #8a93a3; font-size: .9rem; }
  </style>
</head>
<body>

<div class="split-wrap">

  <!-- LEFT IMAGE PANEL -->
  <div class="left-panel">
    <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i>Student Management</span>
    <div class="left-content">
      <h2>Join the Platform Built for <br> Smarter Learning &amp; Teaching</h2>
    </div>
  </div>

  <!-- RIGHT REGISTER PANEL -->
  <div class="right-panel">
    <div class="register-box">
      <div class="mb-4">
        <div class="welcome-title">Create Account</div>
        <div class="welcome-sub">Student Management System</div>
      </div>

      <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Register As</label>
          <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
          </select>
          @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••" required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" placeholder="••••••" required>
        </div>

        <button type="submit" class="btn btn-register w-100 mt-2">Register</button>
      </form>

      <p class="text-center fs-7 mt-4 mb-0" style="color:#6b7280;">
        Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#2563eb;">Sign In</a>
      </p>
    </div>
  </div>

</div>

</body>
</html>