@extends('component.app')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Student List</h2>
        <div class="d-flex gap-2">
            <!-- Add New Student Button -->
            <a href="{{ route('student.register') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Add New Student
            </a>

            <!-- Import Button -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Import Students
            </button>

            <!-- Export PDF Button -->
            <a href="{{ route('students.export-pdf', ['search' => request('search')]) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Bar Component -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6 ms-auto">
            <form action="{{ route('student.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-secondary-subtle">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control" 
                           placeholder="Search by Reg No, Name or Email...">
                    @if(request('search'))
                        <a href="{{ route('student.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Age</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $student->reg_no }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->dob }}</td>
                    <td>{{ $student->age }}</td>
                    <td class="text-center">
                        <!-- View Button -->
                        <a href="{{ route('student.show', $student->id) }}" class="btn btn-sm btn-info text-white me-1">
                            <i class="bi bi-eye"></i> View
                        </a>

                        <!-- Edit Button -->
                        <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('student.destroy', $student->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Students via Excel/CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File (.xlsx, .csv)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx, .csv, .xls" required>
                    </div>
                    <p class="text-muted small">
                        Excel column headers must be: <code>registration_no</code>, <code>full_name</code>, <code>email_address</code>, <code>batch_id</code>, <code>dob</code>, <code>age</code>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection