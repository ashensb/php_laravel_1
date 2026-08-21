@extends('component.app')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-light">Create New Exam / Assignment</h4>
        <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-light btn-sm">Back to List</a>
    </div>

    <div class="card bg-dark border-secondary text-light p-4">
        <form action="{{ route('teacher.exams.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label text-warning">Select Assigned Subject *</label>
                <select name="subject_id" class="form-select bg-secondary text-white border-0" required>
                    <option value="">-- Choose Subject --</option>
                    @foreach($assignedSubjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->subject_code }} - {{ $subject->subject_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" placeholder="e.g. Mid-term MCQ Assessment" class="form-control bg-secondary text-white border-0" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select bg-secondary text-white border-0" required>
                        <option value="mcq">MCQ Exam</option>
                        <option value="assignment">Assignment</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Marks *</label>
                    <input type="number" name="total_marks" value="100" class="form-control bg-secondary text-white border-0" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Time (Optional)</label>
                    <input type="datetime-local" name="start_time" class="form-control bg-secondary text-white border-0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Time (Optional)</label>
                    <input type="datetime-local" name="end_time" class="form-control bg-secondary text-white border-0">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Instructions / Description</label>
                <textarea name="instructions" class="form-control bg-secondary text-white border-0" rows="3" placeholder="Enter special guidelines for students..."></textarea>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck">
                <label class="form-check-label text-info" for="publishCheck">Publish Immediately to Students</label>
            </div>

            <button type="submit" class="btn btn-primary px-4">Create & Proceed</button>
        </form>
    </div>
</div>
@endsection