@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color:#0f1115; min-height:100vh;">

    <!-- Header Section with Filter and CSV Export -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3" style="border-bottom:1px solid #23262f;">
        <div>
            <h3 class="fw-bold mb-1" style="color:#f1f3f7;">Student Submissions</h3>
            <p class="small mb-0" style="color:#8b93a1;">Review, grade, filter submissions, and export Pass/Fail results.</p>
        </div>

        <!-- Dynamic Filter & Export Control Options -->
        <div class="mt-3 mt-md-0 d-flex align-items-center gap-2">
            <!-- Filter & Export Select Box -->
            <select id="examSelect" onchange="filterSubmissions()" class="form-select form-select-sm" style="background:#161a22; border:1px solid #334155; color:#f1f3f7; max-width:260px;">
                <option value="">-- All Exams (Select to Filter) --</option>
                @if(isset($teacherExams) && $teacherExams->count() > 0)
                    @foreach($teacherExams as $exam)
                        <option value="{{ $exam->id }}" {{ (isset($selectedExamId) && $selectedExamId == $exam->id) ? 'selected' : '' }}>
                            {{ $exam->title }}
                        </option>
                    @endforeach
                @endif
            </select>

            <!-- Export CSV Button -->
            <button onclick="exportCSV()" class="btn btn-sm px-3 fw-semibold" style="background:#059669; color:#ffffff; border:none;">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Pass/Fail CSV
            </button>
        </div>
    </div>

    <!-- Submissions Table Card -->
    <div class="rounded-4 shadow-sm" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="--bs-table-bg:#161a22; --bs-table-hover-bg:#1c212b; --bs-table-color:#e5e7eb; --bs-table-border-color:#23262f;">
                <thead>
                    <tr style="background-color:#1a1e27;">
                        <th class="ps-3 small text-uppercase fw-bold py-3" style="color:#6b7280;">Student</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Exam Title</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Score</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Status</th>
                        <th class="small text-uppercase fw-bold py-3" style="color:#6b7280;">Submitted Date</th>
                        <th class="small text-uppercase fw-bold py-3 pe-3" style="color:#6b7280;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr>
                            <td class="ps-3 fw-semibold" style="color:#f1f3f7;">{{ $sub->student->name ?? 'N/A' }}</td>
                            <td style="color:#8b93a1;">{{ $sub->exam->title ?? 'N/A' }}</td>
                            <td style="color:#34d399;" class="fw-bold">
                                {{ $sub->calculated_score ?? $sub->score ?? 'Pending' }}
                            </td>
                            <td>
                                <span class="badge px-2 py-1 text-uppercase" style="background:{{ strtolower($sub->status) === 'pending' ? '#854d0e' : '#064e3b' }}; color:{{ strtolower($sub->status) === 'pending' ? '#fef08a' : '#34d399' }};">
                                    {{ $sub->status }}
                                </span>
                            </td>
                            <td style="color:#8b93a1;">
                                {{ $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->format('Y-m-d h:i A') : 'N/A' }}
                            </td>
                            <td class="pe-3">
                                <a href="{{ route('teacher.submissions.show', $sub->id) }}" class="btn btn-sm" style="border:1px solid #3b82f6; color:#60a5fa; background:transparent;">Review & Grade</a>
                                <a href="{{ url('/teacher/export-exam-report/' . $sub->exam_id) }}" class="btn btn-sm btn-outline-success ms-1" title="Export this exam report">
                                    <i class="bi bi-download"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color:#8b93a1;">No submissions found for the selected criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $submissions->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Dynamic Filter and Export Route Handling Scripts -->
<script>
    // 1. Filter Functionality
    function filterSubmissions() {
        const examId = document.getElementById('examSelect').value;
        const url = new URL(window.location.href);
        if (examId) {
            url.searchParams.set('exam_id', examId);
        } else {
            url.searchParams.delete('exam_id');
        }
        window.location.href = url.toString();
    }

    // 2. Export Functionality (Supports Filtered or All Submissions)
    function exportCSV() {
        const examId = document.getElementById('examSelect').value;
        if (examId) {
            window.location.href = `/teacher/export-exam-report/${examId}`;
        } else {
            window.location.href = `/teacher/export-exam-report`;
        }
    }
</script>
@endsection