@extends('app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Student Registration</h3>
            <p class="text-muted small mb-0">Fill in the details below to register a new student to the system.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student Register</li>
            </ol>
        </nav>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="bi bi-person-plus-fill me-2"></i>New Student Details
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Reg No -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Registration No <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-card-heading"></i></span>
                            <input type="text" name="reg_no" class="form-control" placeholder="e.g. STU-2026-001" required>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                    </div>

                    <!-- Assign Batch (NEWLY ADDED FIELD) -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Assign Batch</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-journal-check"></i></span>
                            <select name="batch_id" class="form-select">
                                <option value="">-- Select Batch --</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->batch_name }} ({{ $batch->course_name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Date of Birth <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                    </div>

                    <!-- Age -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Age <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-hash"></i></span>
                            <input type="number" name="age" class="form-control" placeholder="20" min="1" max="100" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter secure password" required>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Image <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-image"></i></span>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                </div>

                


                <hr class="my-4">

                <!-- Submit & Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light px-4 border">Reset</button>
                    <button type="submit" class="btn btn-primary px-4 fw-medium">
                        <i class="bi bi-save me-1"></i> Save Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection