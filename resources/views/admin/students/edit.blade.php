@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Student</h3>
            <p class="text-muted small mb-0">Update student information below.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.index') }}" class="text-decoration-none">Students List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
            </ol>
        </nav>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="bi bi-pencil-square me-2"></i>Edit Student Details
            </h5>
        </div>

        <div class="card-body p-4">
            <!-- enctype="multipart/form-data"  -->
            <form action="{{ route('student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Reg No -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Registration No <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-card-heading"></i></span>
                            <input type="text" name="reg_no" value="{{ $student->reg_no }}" class="form-control" required>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ $student->email }}" class="form-control" required>
                        </div>
                    </div>

                    <!-- Assign Batch Dropdown -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Assign Batch</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-journal-check"></i></span>
                            <select name="batch_id" class="form-select">
                                <option value="">-- Select Batch --</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ $student->batch_id == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->batch_name }} ({{ $batch->course_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Date of Birth <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="dob" value="{{ $student->dob }}" class="form-control" required>
                        </div>
                    </div>

                    <!-- Age -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Age <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-hash"></i></span>
                            <input type="number" name="age" value="{{ $student->age }}" class="form-control" min="1" max="100" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" value="{{ $student->password }}" class="form-control" required>
                        </div>
                    </div>

                    <!-- Image Upload with Current Image Preview -->
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Profile Image</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-image"></i></span>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <small class="text-muted d-block mt-1">අලුත් Image එකක් තෝරා නොගතහොත් දැනට පවතින Image එක වෙනස් නොවේ.</small>

                        <!-- Current Image Display -->
                        @if($student->img)
                            <div class="mt-2">
                                <span class="d-block small text-muted mb-1">Current Image:</span>
                                <img src="{{ asset($student->img) }}" alt="Student Image" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <!-- Submit & Cancel Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('student.index') }}" class="btn btn-light px-4 border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 fw-medium">
                        <i class="bi bi-check-circle me-1"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection