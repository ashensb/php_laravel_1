<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    .split-wrap {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    /* LEFT PANEL */
    .left-panel {
      position: relative;
      flex: 1 1 55%;
      background: linear-gradient(160deg, rgba(14, 59, 77, 0.55) 0%, rgba(20, 92, 115, 0.55) 100%),
                  url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=80');
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
    }
    @media (max-width: 991px) {
      .right-panel { clip-path: none; margin-left: 0; }
      .split-wrap { flex-direction: column; }
      .left-panel { flex: 0 0 auto; min-height: 220px; padding: 30px; }
      body, html { overflow: auto; }
    }

    .login-box { width: 100%; max-width: 360px; padding: 20px 30px; }

    .form-control {
      border-color: #e3e6ec;
      padding: 10px 14px;
      font-size: .92rem;
    }
    .form-control:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 0.2rem rgba(37,99,235,.15);
    }
    .form-label { color: #374151; font-size: .85rem; font-weight: 600; }

    .btn-signin {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 11px;
      border-radius: 10px;
      transition: opacity .15s ease;
    }
    .btn-signin:hover { opacity: .92; color:#fff; }

    .welcome-title {
      font-weight: 800;
      font-size: 2rem;
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
      <h2>Empowering Students &amp; Teachers <br> with a Smarter Learning Platform</h2>
    </div>
  </div>

  <!-- RIGHT LOGIN PANEL -->
  <div class="right-panel">
    <div class="login-box">
      <div class="mb-4">
        <div class="welcome-title">Welcome Back</div>
        <div class="welcome-sub">Sign in to your account</div>
      </div>

      @if(session('success'))
        <div class="alert alert-success fs-7 py-2 border-0" style="background:#e8f8ee; color:#166534;">
          <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
        </div>
      @endif

      <form action="{{ route('login.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required autocomplete="email">
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-2">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label small" for="remember" style="color:#6b7280;">Remember me</label>
          </div>
          <a href="#" class="small text-decoration-none" style="color:#2563eb;">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-signin w-100">Sign In</button>
      </form>

      <p class="text-center fs-7 mt-4 mb-0" style="color:#6b7280;">
        Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#2563eb;">Register</a>
      </p>
    </div>
  </div>

</div>

</body>
</html>