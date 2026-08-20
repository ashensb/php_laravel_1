@extends('component.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-white">Subject Management</h2>
            <p class="text-secondary">Create and manage subjects for each course.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Add Subject Form -->
        <div class="col-md-4">
            <div class="card bg-dark text-white border-secondary shadow-sm">
                <div class="card-header border-secondary fw-bold">
                    Add New Subject
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subjects.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-secondary">Select Course</label>
                            <select name="course_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- Choose Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Subject Code</label>
                            <input type="text" name="subject_code" class="form-control bg-dark text-white border-secondary" placeholder="e.g. SE3010" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Subject Name</label>
                            <input type="text" name="subject_name" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Database Systems" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Subject</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Subjects List Table -->
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-sm">
                <div class="card-header border-secondary fw-bold">
                    All Subjects List
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-secondary">
                                    <th>Course</th>
                                    <th>Code</th>
                                    <th>Subject Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                    <tr>
                                        <td><span class="badge bg-info text-dark">{{ $subject->course_name }}</span></td>
                                        <td class="fw-bold">{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->subject_name }}</td>
                                        <td>
                                            <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this subject?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No subjects created yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection