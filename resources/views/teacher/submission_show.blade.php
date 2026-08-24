@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color:#0f1115; min-height:100vh;">

    <!-- Top Navigation Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-4 rounded-4 shadow-sm" style="background:#161a22; border-left:4px solid #3b82f6;">
        <div>
            <h4 class="fw-bold mb-1" style="color:#f1f3f7;">Submission Evaluation - {{ $submission->exam->title }}</h4>
            <p class="mb-0" style="color:#8b93a1;">Student: <strong class="text-white">{{ $submission->student->name ?? 'Student #'.$submission->student_id }}</strong></p>
        </div>
        <a href="{{ route('teacher.submissions') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Submissions
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-dark text-success border-success mb-4 rounded-3">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Score Summary & Teacher Feedback Card -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="p-4 rounded-4 text-center" style="background:#161a22; border:1px solid #23262f;">
                <span class="text-uppercase small fw-bold text-muted">Auto Calculated Score</span>
                <h1 class="display-5 fw-bold my-2" style="color:#60a5fa;">{{ $totalScore }} / {{ $maxScore }}</h1>
                @php
                    $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
                @endphp
                <span class="badge {{ $percentage >= 50 ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                    Score Ratio: {{ $percentage }}%
                </span>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="p-4 rounded-4 h-100" style="background:#161a22; border:1px solid #23262f;">
                <h6 class="fw-bold text-white mb-2"><i class="bi bi-chat-left-text me-2 text-primary"></i> Teacher's Feedback</h6>
                <form action="{{ route('teacher.submissions.grade', $submission->id) }}" method="POST">
                    @csrf
                    <textarea name="teacher_feedback" class="form-control bg-dark text-white border-secondary mb-3" rows="2" placeholder="Write feedback for student...">{{ $submission->teacher_feedback }}</textarea>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-1"></i> Save Grade & Publish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Questions Detailed Evaluation -->
    <div class="card bg-dark text-white border-secondary mb-4">
        <div class="card-header bg-transparent border-secondary py-3">
            <h5 class="mb-0 text-white"><i class="bi bi-list-check text-info me-2"></i> Detailed Answers Evaluation</h5>
        </div>
        <div class="card-body">
            @forelse($submission->exam->questions as $index => $question)
                @php
                    // Student Answer එක Key 3කින්ම Check කිරීම
                    $selectedVal = $studentAnswers[$question->id] 
                                 ?? $studentAnswers[(string)$question->id] 
                                 ?? $studentAnswers[$index] 
                                 ?? null;

                    if (is_array($selectedVal)) {
                        $selectedVal = $selectedVal['option_id'] ?? $selectedVal['answer'] ?? $selectedVal[0] ?? null;
                    }

                    $correctOption = $question->options->where('is_correct', true)->first();
                    
                    // User selection Correct ද යන්න පරීක්ෂාව
                    $isUserCorrect = false;
                    if ($correctOption && $selectedVal !== null) {
                        if ($selectedVal == $correctOption->id || trim(strtolower((string)$selectedVal)) === trim(strtolower((string)$correctOption->option_text))) {
                            $isUserCorrect = true;
                        }
                    }
                @endphp
                
                <div class="mb-4 p-3 rounded-3" style="background-color:#161a22; border:1px solid {{ $isUserCorrect ? '#15803d' : '#991b1b' }};">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-light">{{ $index + 1 }}. {{ $question->question }}</h5>
                        <div>
                            @if($isUserCorrect)
                                <span class="badge bg-success me-2">+{{ $question->marks ?? 1 }} Marks</span>
                            @else
                                <span class="badge bg-danger me-2">0 Marks</span>
                            @endif
                            <span class="badge bg-secondary">{{ $question->marks ?? 1 }} Total Marks</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @foreach($question->options as $option)
                            @php
                                $isSelected = false;
                                if ($selectedVal !== null) {
                                    if ($selectedVal == $option->id || trim(strtolower((string)$selectedVal)) === trim(strtolower((string)$option->option_text))) {
                                        $isSelected = true;
                                    }
                                }
                                $isCorrect = (bool)$option->is_correct;
                            @endphp

                            <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" 
                                 style="background-color: {{ $isSelected ? ($isCorrect ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.25)') : ($isCorrect ? 'rgba(34, 197, 94, 0.1)' : '#0f1115') }}; 
                                        border: 2px solid {{ $isSelected ? ($isCorrect ? '#22c55e' : '#ef4444') : ($isCorrect ? '#22c55e' : '#23262f') }};">
                                
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $isSelected ? ($isCorrect ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger') : ($isCorrect ? 'bi-check-circle text-success' : 'bi-circle text-muted') }}"></i>
                                    <span style="color: {{ $isSelected || $isCorrect ? '#ffffff' : '#9ca3af' }}; font-weight: {{ $isSelected ? 'bold' : 'normal' }};">
                                        {{ $option->option_text }}
                                    </span>
                                </div>

                                <div>
                                    @if($isSelected && $isCorrect)
                                        <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Selected & Correct</span>
                                    @elseif($isSelected && !$isCorrect)
                                        <span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i> Student Selected (Wrong)</span>
                                    @elseif($isCorrect)
                                        <span class="badge border border-success text-success"><i class="bi bi-check2 me-1"></i> Correct Option</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-muted">No questions found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection