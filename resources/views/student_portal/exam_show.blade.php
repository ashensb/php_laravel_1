@extends('component.app')

@section('content')
<div class="container py-4" style="background-color:#0f1115; min-height:100vh; color:#f1f3f7;">
    <div class="card bg-dark text-white border-secondary mb-4">
        <div class="card-header border-secondary d-flex justify-content-between align-items-center py-3" style="background-color:#161a22;">
            <div>
                <h4 class="mb-1 text-white fw-bold">{{ $exam->title }}</h4>
                <small class="text-muted">Total Marks: {{ $exam->total_marks }}</small>
            </div>
            <span class="badge bg-primary px-3 py-2 fs-6">{{ strtoupper($exam->type) }}</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('student.exam.submit', $exam->id) }}" method="POST">
                @csrf
                
                @forelse($exam->questions as $index => $question)
                    <div class="mb-4 p-4 rounded-3" style="background-color:#161a22; border:1px solid #23262f;">
                        <!-- Question Text and Marks -->
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                            <h5 class="mb-0 text-light fw-bold">{{ $index + 1 }}. {{ $question->question }}</h5>
                            <span class="badge bg-secondary">{{ $question->marks }} Marks</span>
                        </div>
                        
                        <!-- Options / Answer Input -->
                        @if($exam->type === 'mcq')
                            <div class="mt-3">
                                @foreach($question->options as $option)
                                    <div class="form-check my-2 ps-4 p-2 rounded" style="background-color: #0f1115; border: 1px solid #23262f;">
                                        <input class="form-check-input ms-0 me-2" 
                                               type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="{{ $option->id }}" 
                                               id="opt_{{ $option->id }}" 
                                               required>
                                        <label class="form-check-label text-light w-100" for="opt_{{ $option->id }}" style="cursor: pointer;">
                                            {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Written / Assignment Input -->
                            <div class="mt-3">
                                <textarea name="answers[{{ $question->id }}]" 
                                          class="form-control bg-dark text-white border-secondary" 
                                          rows="4" 
                                          placeholder="Write your answer here..." 
                                          required></textarea>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-exclamation-circle text-muted fs-1 mb-2"></i>
                        <p class="text-muted mb-0">No questions found in this exam.</p>
                    </div>
                @endforelse

                @if($exam->questions->count() > 0)
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" 
                                class="btn btn-success px-4 py-2 fw-semibold" 
                                onclick="return confirm('Are you sure you want to submit your exam?')">
                            <i class="bi bi-send me-1"></i> Submit Exam
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection