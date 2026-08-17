@extends('component.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Teacher Profile</h2>
        <a href="{{ route('teacher.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Teacher List
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-center p-3 mb-4 shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        @if($teacher->img)
                            <img src="{{ asset('storage/' . $teacher->img) }}" alt="Teacher Profile" class="rounded-circle img-fluid shadow-sm" style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/140" alt="Default Avatar" class="rounded-circle img-fluid shadow-sm">
                        @endif
                    </div>
                    <h3 class="fw-bold">{{ $teacher->name }}</h3>
                    <p class="text-muted">{{ $teacher->qualification ?? 'Lecturer' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Teacher Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Full Name</th>
                                <td>{{ $teacher->name }}</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $teacher->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $teacher->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualification</th>
                                <td>{{ $teacher->qualification ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Joined Date</th>
                                <td>{{ $teacher->created_at ? $teacher->created_at->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <a href="{{ route('teacher.edit', $teacher->id) }}" class="btn btn-warning">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection