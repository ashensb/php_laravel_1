@extends('component.app')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-light">Manage Questions - {{ $exam->title }}</h4>
        <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-light btn-sm">Done & Return</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Question Create Form -->
    <div class="card bg-dark border-secondary text-light p-4 mb-4">
        <h5 class="text-info mb-3">Add New Question</h5>
        <form action="{{ route('teacher.exams.questions.store', $exam->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-10 mb-3">
                    <label class="form-label">Question Statement *</label>
                    <textarea name="question" class="form-control bg-secondary text-white border-0" rows="2" placeholder="Write question text..." required></textarea>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Marks</label>
                    <input type="number" name="marks" value="1" class="form-control bg-secondary text-white border-0" min="1">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Options (Select radio for correct answer) *</label>
                @for($i = 0; $i < 4; $i++)
                    <div class="input-group mb-2">
                        <div class="input-group-text bg-secondary border-0 text-white">
                            <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }} required>
                        </div>
                        <input type="text" name="options[]" class="form-control bg-secondary text-white border-0" placeholder="Option {{ $i+1 }}" required>
                    </div>
                @endfor
            </div>

            <button type="submit" class="btn btn-success">Save Question</button>
        </form>
    </div>

    <!-- Existing Questions List -->
    <div class="card bg-dark border-secondary text-light p-4">
        <h5 class="text-light mb-3">Added Questions ({{ $exam->questions->count() }})</h5>
        @forelse($exam->questions as $index => $q)
            <div class="border border-secondary p-3 rounded mb-3 bg-dark">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-warning">Q{{ $index + 1 }}: {{ $q->question }} <span class="badge bg-secondary ms-2">{{ $q->marks }} Mark(s)</span></h6>
                    <form action="{{ route('teacher.exams.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger border-0">Delete</button>
                    </form>
                </div>
                <ul class="list-group mt-2">
                    @foreach($q->options as $opt)
                        <li class="list-group-item bg-secondary text-white border-dark d-flex justify-content-between align-items-center">
                            {{ $opt->option_text }}
                            @if($opt->is_correct)
                                <span class="badge bg-success">Correct Answer</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-muted mb-0">No questions added yet to this exam.</p>
        @endforelse
    </div>
</div>
@endsection