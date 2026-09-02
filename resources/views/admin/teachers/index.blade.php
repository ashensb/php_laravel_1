@extends('component.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Area -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Teacher List</h2>
        <div class="d-flex gap-2">
            <!-- Import Teachers Button -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importTeacherModal">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Import Teachers
            </button>

            <!-- Export PDF Button -->
            <a href="{{ route('teachers.export-pdf', ['search' => request('search')]) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>

            <!-- Add New Teacher Button -->
            <a href="{{ route('teacher.create') }}" class="btn btn-primary">+ Add New Teacher</a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6 ms-auto">
            <form action="{{ route('teacher.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-secondary-subtle">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control" 
                           placeholder="Search by Name, Email or Qualification...">
                    @if(request('search'))
                        <a href="{{ route('teacher.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->phone ?? 'N/A' }}</td>
                        <td>{{ $teacher->qualification ?? 'N/A' }}</td>
                        <td>
                            <!-- View Button -->
                            <a href="{{ route('teacher.show', $teacher->id) }}" class="btn btn-sm btn-info text-white">View</a>

                            <!-- Edit Button -->
                            <a href="{{ route('teacher.edit', $teacher->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <!-- Delete Form -->
                            <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">No teachers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Bulk Import Teachers</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Excel/CSV File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection