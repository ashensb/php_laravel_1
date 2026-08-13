<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-body-secondary d-flex align-items-center justify-content-center min-vh-100">

<div class="card shadow-sm border-0" style="width: 380px;">
  <div class="card-body p-4">
    <h3 class="text-center fw-bold text-primary mb-1">Welcome Back</h3>
    <p class="text-center text-muted fs-7 mb-4">Sign in to your account</p>

    @if(session('success'))
      <div class="alert alert-success fs-7 py-2">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required autocomplete="email">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="******" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 fw-semibold mt-2">Sign In</button>
    </form>

    <p class="text-center fs-7 mt-3 mb-0">
      Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Register</a>
    </p>
  </div>
</div>

</body>
</html>