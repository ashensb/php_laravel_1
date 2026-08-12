@extends('app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm col-md-8 mx-auto">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add New Teacher</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Qualification / Designation</label>
                    <input type="text" name="qualification" class="form-control" placeholder="e.g. Senior Lecturer / BSc in CS">
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('teacher.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection