@extends('component.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-white">Assign Subjects to Teachers</h2>
            <p class="text-secondary">Allocate course subjects to respective teachers.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Assign Form -->
        <div class="col-md-4">
            <div class="card bg-dark text-white border-secondary shadow-sm">
                <div class="card-header border-secondary fw-bold">
                    Assign New Subject
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subject-teacher.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-secondary">Select Teacher</label>
                            <select name="teacher_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- Choose Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? 'N/A' }} ({{ $teacher->reg_no }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Select Subject</label>
                            <select name="subject_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- Choose Subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Assign Subject</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assigned List Table -->
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-sm">
                <div class="card-header border-secondary fw-bold">
                    Current Subject Allocations
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-secondary">
                                    <th>Teacher Name</th>
                                    <th>Assigned Subjects</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                    <tr>
                                        <td class="fw-bold">{{ $teacher->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @forelse($teacher->subjects as $subject)
                                                <span class="badge bg-primary me-1 mb-1 p-2">
                                                    {{ $subject->subject_code }} - {{ $subject->subject_name }}
                                                    <form action="{{ route('admin.subject-teacher.destroy', [$teacher->id, $subject->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-close btn-close-white ms-1" style="font-size: 0.65rem;" onclick="return confirm('Remove this subject assignment?')"></button>
                                                    </form>
                                                </span>
                                            @empty
                                                <span class="text-muted small">No subjects assigned yet</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-3 text-muted">No teachers found.</td>
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