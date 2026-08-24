@extends('component.app') {{-- MASTER LAYOUT --}}

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Student Profile</h2>
        <a href="{{ route('student.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Student List
        </a>
    </div>

    <div class="row">
        <!-- Left Column: Profile Card -->
        <div class="col-md-4">
            <div class="card card-primary card-outline text-center p-3 mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        @if($student->img)
                            <img src="{{ asset('storage/' . $student->img) }}" alt="User Profile" class="rounded-circle img-fluid shadow-sm" style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/140" alt="Default Avatar" class="rounded-circle img-fluid shadow-sm">
                        @endif
                    </div>
                    <h3 class="profile-username text-center fw-bold">{{ $student->name }}</h3>
                    <p class="text-muted text-center mb-1">Registration No: <strong>{{ $student->reg_no }}</strong></p>
                    <span class="badge bg-success fs-6">{{ $student->batch->batch_name ?? 'No Batch Assigned' }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Student Details -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Personal & Academic Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Full Name</th>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <th>Registration No</th>
                                <td>{{ $student->reg_no }}</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $student->email }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $student->dob }}</td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td>{{ $student->age }} Years</td>
                            </tr>
                            <tr>
                                <th>Assigned Batch</th>
                                <td>{{ $student->batch->batch_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Course</th>
                                <td>{{ $student->batch->course_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Registered Date</th>
                                <td>{{ $student->created_at ? $student->created_at->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('student.edit', $student->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection