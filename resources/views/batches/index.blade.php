@extends('app')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Batch Management</h3>
            <p class="text-muted small mb-0">Create and manage courses and student batches.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Add New Batch Form -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold py-3">
                    <i class="bi bi-plus-circle me-2"></i>Add New Batch
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('batch.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Batch Name</label>
                            <input type="text" name="batch_name" class="form-control" placeholder="e.g. Batch 01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Course Name</label>
                            <input type="text" name="course_name" class="form-control" placeholder="e.g. Web Development" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Assign Teacher</label>
                            <select name="teacher_id" class="form-select">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-save me-1"></i> Save Batch
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Batches List Table -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-body-tertiary fw-bold py-3">
                    <i class="bi bi-journal-bookmark me-2 text-primary"></i>All Batches
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Batch</th>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th>Start Date</th>
                                    <th>Total Students</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batches as $batch)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-primary">{{ $batch->batch_name }}</td>
                                        <td>{{ $batch->course_name }}</td>
                                        <td>
                                            @if($batch->teacher)
                                                <span class="badge bg-secondary-subtle text-dark border fw-medium">
                                                    <i class="bi bi-person-badge me-1"></i>{{ $batch->teacher->name }}
                                                </span>
                                            @else
                                                <span class="text-muted small">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $batch->start_date ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info fw-bold fs-6">
                                                {{ $batch->students_count }} Students
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('batch.destroy', $batch->id) }}" method="POST" onsubmit="return confirm('Delete this batch?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No batches created yet.</td>
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