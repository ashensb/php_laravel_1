@extends('component.app')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-light">Exam & Quiz Management</h4>
        <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary btn-sm rounded-2">
            <i class="bi bi-plus-lg me-1"></i> Create New Exam
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0 alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary text-light">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr class="border-secondary text-secondary">
                            <th>Title</th>
                            <th>Type</th>
                            <th>Total Marks</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                        <tr>
                            <td class="fw-bold text-white">{{ $exam->title }}</td>
                            <td>
                                <span class="badge bg-{{ $exam->type === 'mcq' ? 'info' : 'warning' }} text-dark">
                                    {{ strtoupper($exam->type) }}
                                </span>
                            </td>
                            <td>{{ $exam->total_marks }}</td>
                            <td>
                                <form action="{{ route('teacher.exams.toggle-publish', $exam->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm badge border-0 bg-{{ $exam->is_published ? 'success' : 'secondary' }}" title="Click to toggle publish status">
                                        {{ $exam->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($exam->type === 'mcq')
                                        <a href="{{ route('teacher.exams.questions', $exam->id) }}" class="btn btn-sm btn-outline-info">
                                            Manage Questions ({{ $exam->questions->count() }})
                                        </a>
                                    @endif

                                    <form action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this exam and all associated questions?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No exams or assignments created yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection