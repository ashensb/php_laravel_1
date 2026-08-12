@extends('app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Teacher List</h2>
        <a href="{{ route('teacher.create') }}" class="btn btn-primary">+ Add New Teacher</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
@endsection