@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color:#0f1115; min-height:100vh;">
    
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3" style="border-bottom:1px solid #23262f;">
        <div>
            <h3 class="fw-bold mb-1" style="color:#f1f3f7;">Exam Result & Review</h3>
            <p class="small mb-0" style="color:#8b93a1;">{{ $submission->exam->title }} - {{ $submission->exam->subject->subject_name ?? '' }}</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-sm px-3" style="background:#1e293b; border:1px solid #334155; color:#cbd5e1;">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Overview Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="rounded-4 p-4 shadow-sm" style="background:#161a22; border:1px solid #23262f;">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-uppercase fw-bold d-block mb-1" style="color:#8b93a1; letter-spacing:0.5px;">Final Score</small>
                        <h2 class="fw-bold mb-0" style="color:#34d399;">
                            {{ $submission->score ?? 0 }} <span style="font-size: 1.2rem; color:#8b93a1;">/ {{ $submission->exam->total_marks }}</span>
                        </h2>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-uppercase fw-bold d-block mb-1" style="color:#8b93a1; letter-spacing:0.5px;">Status</small>
                        <span class="badge px-3 py-2 text-uppercase" style="background:#064e3b; color:#34d399; border:1px solid #059669;">
                            {{ strtoupper($submission->status ?? 'Graded') }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-uppercase fw-bold d-block mb-1" style="color:#8b93a1; letter-spacing:0.5px;">Submitted At</small>
                        <span style="color:#f1f3f7;">{{ \Carbon\Carbon::parse($submission->submitted_at)->format('Y-m-d h:i A') }}</span>
                    </div>
                </div>

                @php
                    $overallFeedback = $submission->feedback ?? $submission->teacher_feedback;
                @endphp
                @if($overallFeedback)
                    <div class="mt-4 p-3 rounded-3" style="background:#1a2332; border-left:4px solid #60a5fa;">
                        <h6 class="fw-bold mb-1" style="color:#60a5fa;"><i class="bi bi-chat-quote-fill me-2"></i>Teacher's Overall Feedback</h6>
                        <p class="mb-0 text-light italic">"{{ $overallFeedback }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Questions Review -->
    <div class="rounded-4 p-4 shadow-sm" style="background:#161a22; border:1px solid #23262f;">
        <h5 class="fw-bold mb-4" style="color:#f1f3f7;"><i class="bi bi-list-check me-2" style="color:#60a5fa;"></i>Question Breakdown</h5>

        @foreach($submission->exam->questions as $index => $question)
            @php
                $userSelected = $studentAnswers[$question->id] 
                             ?? $studentAnswers[(string)$question->id] 
                             ?? $studentAnswers[$index] 
                             ?? null;

                if (is_array($userSelected)) {
                    $userSelected = $userSelected['option_id'] ?? $userSelected['answer'] ?? $userSelected[0] ?? null;
                }

                $correctOption = $question->options->where('is_correct', true)->first();
                $isCorrect = false;

                if ($correctOption && $userSelected !== null) {
                    if ($userSelected == $correctOption->id || trim(strtolower((string)$userSelected)) === trim(strtolower((string)$correctOption->option_text))) {
                        $isCorrect = true;
                    }
                }
            @endphp

            <div class="p-3 mb-3 rounded-3" style="background:#0f1115; border:1px solid {{ $isCorrect ? '#059669' : '#991b1b' }};">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0" style="color:#f1f3f7;">
                        Q{{ $index + 1 }}. {{ $question->question_text }}
                    </h6>
                    <div>
                        @if($isCorrect)
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Correct (+{{ $question->marks ?? 1 }})</span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Incorrect (0)</span>
                        @endif
                    </div>
                </div>

                <!-- Options List -->
                <div class="mt-3">
                    @foreach($question->options as $option)
                        @php
                            $isSelectedOption = ($userSelected == $option->id || trim(strtolower((string)$userSelected)) === trim(strtolower((string)$option->option_text)));
                            $isThisCorrect = $option->is_correct;

                            $bgStyle = '#161a22';
                            $borderStyle = '1px solid #23262f';
                            $textColor = '#8b93a1';

                            if ($isThisCorrect) {
                                $bgStyle = '#064e3b';
                                $borderStyle = '1px solid #059669';
                                $textColor = '#34d399';
                            } elseif ($isSelectedOption && !$isThisCorrect) {
                                $bgStyle = '#451a1a';
                                $borderStyle = '1px solid #991b1b';
                                $textColor = '#f87171';
                            }
                        @endphp

                        <div class="p-2 px-3 mb-2 rounded d-flex justify-content-between align-items-center" style="background:{{ $bgStyle }}; border:{{ $borderStyle }}; color:{{ $textColor }};">
                            <span>{{ $option->option_text }}</span>
                            <span>
                                @if($isSelectedOption && $isThisCorrect)
                                    <small class="fw-bold"><i class="bi bi-person-check-fill me-1"></i> Your Choice & Correct Answer</small>
                                @elseif($isSelectedOption && !$isThisCorrect)
                                    <small class="fw-bold"><i class="bi bi-person-x-fill me-1"></i> Your Answer</small>
                                @elseif($isThisCorrect)
                                    <small class="fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Correct Answer</small>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection