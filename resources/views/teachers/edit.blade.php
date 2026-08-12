@extends('app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm col-md-8 mx-auto">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Teacher Details</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $teacher->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ $teacher->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ $teacher->phone }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Qualification / Designation</label>
                    <input type="text" name="qualification" class="form-control" value="{{ $teacher->qualification }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Change Profile Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('teacher.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection