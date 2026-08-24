@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color:#0f1115; min-height:100vh;">

    <!-- Title Banner -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-4 rounded-4 shadow-sm" style="background:#161a22; border-left:4px solid #3b82f6;">
        <div>
            <h3 class="fw-bold mb-1" style="color:#f1f3f7;">Student Submissions</h3>
            <p class="mb-0" style="color:#8b93a1;">Review and grade exam submissions from your students</p>
        </div>
        <span class="badge px-3 py-2 fs-6" style="background-color:#3b82f6; color:#ffffff;">Answers Log</span>
    </div>

    <!-- Submissions Table -->
    <div class="rounded-4 shadow-sm" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
        <div class="fw-bold py-3 px-3" style="background:#1a1e27; color:#f1f3f7; border-bottom:1px solid #23262f;">
            <i class="bi bi-file-earmark-text me-2" style="color:#60a5fa;"></i> All Exam Answers & Submissions
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="--bs-table-bg:#161a22; --bs-table-hover-bg:#1c212b; --bs-table-color:#e5e7eb; --bs-table-border-color:#23262f;">
                <thead>
                    <tr style="background-color:#1a1e27;">
                        <th class="ps-3 small text-uppercase fw-bold py-3" style="color:#6b7280;">Student Name</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Exam Title</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Submission Time</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Status</th>
                        <th class="small text-uppercase fw-bold py-3 pe-3 text-end" style="color:#6b7280;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $submission->student->name ?? $submission->student->name ?? 'Student #' . $submission->student_id }}

                            </td>
                            <td style="color:#8b93a1;">{{ $submission->exam->title ?? 'N/A' }}</td>
                            <td style="color:#8b93a1;">{{ \Carbon\Carbon::parse($submission->submitted_at)->format('Y-m-d h:i A') }}</td>
                            <td>
                                @if($submission->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Graded</span>
                                @endif
                            </td>
                            <td class="pe-3 text-end">
                                 <a href="{{ route('teacher.submissions.show', $submission->id) }}"   class="btn btn-sm" style="border:1px solid #3b82f6; color:#60a5fa; background:transparent;">
                                          View Answers
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color:#8b93a1;">No submissions found for your exams yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection