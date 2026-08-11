@extends('app')

@section('content')
<div class="container mt-4">
    <h2>Edit Student</h2>

    <form action="{{ route('student.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Registration No</label>
            <input type="text" name="reg_no" value="{{ $student->reg_no }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" value="{{ $student->email }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" value="{{ $student->dob }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" value="{{ $student->age }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="text" name="password" value="{{ $student->password }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Update Student</button>
        <a href="{{ route('student.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection