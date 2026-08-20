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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
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
                        
                        <!-- Select Teacher -->
                        <div class="mb-3">
                            <label class="form-label text-secondary">Select Teacher</label>
                            <select name="teacher_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- Choose Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">
                                        {{ $teacher->name }} @if($teacher->qualification) ({{ $teacher->qualification }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Course -->
                        <div class="mb-3">
                            <label class="form-label text-secondary">Select Course</label>
                            <select id="course_dropdown" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- Choose Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dynamic Select Subject -->
                        <div class="mb-3">
                            <label class="form-label text-secondary">Select Subject</label>
                            <select name="subject_id" id="subject_dropdown" class="form-select bg-dark text-white border-secondary" required disabled>
                                <option value="">-- Select Course First --</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Assign Subject</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Allocation Table -->
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
                                    <th>Teacher Details</th>
                                    <th>Assigned Subjects</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $teacher->name }}</div>
                                            @if($teacher->qualification)
                                                <small class="text-secondary d-block">{{ $teacher->qualification }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse($teacher->assigned_subjects as $subj)
                                                <div class="d-inline-block bg-primary text-white rounded px-2 py-1 me-1 mb-1 small">
                                                    {{ $subj->subject_name }} ({{ $subj->subject_code }})
                                                    <form action="{{ route('admin.subject-teacher.destroy', [$teacher->id, $subj->id]) }}" method="POST" class="d-inline ms-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-close btn-close-white ms-1" style="font-size: 0.65rem;" onclick="return confirm('Remove subject assignment?')"></button>
                                                    </form>
                                                </div>
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

<!-- Dynamic Dropdown Script -->
<script>
document.getElementById('course_dropdown').addEventListener('change', function() {
    var courseId = this.value;
    var subjectDropdown = document.getElementById('subject_dropdown');

    subjectDropdown.innerHTML = '<option value="">Loading...</option>';
    subjectDropdown.disabled = true;

    if (courseId) {
        fetch('/admin/get-subjects-by-course/' + courseId)
            .then(response => response.json())
            .then(data => {
                subjectDropdown.innerHTML = '<option value="">-- Choose Subject --</option>';
                if (data.length > 0) {
                    data.forEach(function(subject) {
                        var option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.subject_name + ' (' + subject.subject_code + ')';
                        subjectDropdown.appendChild(option);
                    });
                    subjectDropdown.disabled = false;
                } else {
                    subjectDropdown.innerHTML = '<option value="">No subjects found for this course</option>';
                }
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                subjectDropdown.innerHTML = '<option value="">Error loading subjects</option>';
            });
    } else {
        subjectDropdown.innerHTML = '<option value="">-- Select Course First --</option>';
    }
});
</script>
@endsection