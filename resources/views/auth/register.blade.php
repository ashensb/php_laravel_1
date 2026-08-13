<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LMS | Register</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-body-secondary d-flex align-items-center justify-content-center min-vh-100">

<div class="card shadow-sm border-0" style="width: 400px;">
  <div class="card-body p-4">
    <h3 class="text-center fw-bold text-primary mb-1">Create Account</h3>
    <p class="text-center text-muted fs-7 mb-4">Student Management System</p>

    <form action="{{ route('register.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Full Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Register As</label>
        <select name="role" class="form-select fs-7 @error('role') is-invalid @enderror" required>
          <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
          <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
          <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="******" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fs-7 fw-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="******" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 fw-semibold mt-2">Register</button>
    </form>

    <p class="text-center fs-7 mt-3 mb-0">
      Already have an account? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Sign In</a>
    </p>
  </div>
</div>

</body>
</html>