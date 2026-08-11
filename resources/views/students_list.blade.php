@extends('app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student List</h2>
        <a href="{{ route('student.register') }}" class="btn btn-primary">Add New Student</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Reg No</th>
                <th>Name</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->reg_no }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->dob }}</td>
                <td>{{ $student->age }}</td>
                <td>
                    <!-- Edit Button -->
                    <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>

                    <!-- Delete Form -->
                    <form action="{{ route('student.destroy', $student->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection