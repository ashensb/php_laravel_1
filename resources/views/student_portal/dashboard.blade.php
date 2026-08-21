@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color:#0f1115; min-height:100vh;">

    <!-- Header Part -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3" style="border-bottom:1px solid #23262f;">
        <div>
            <h3 class="fw-bold mb-1" style="color:#f1f3f7;">Welcome, {{ Auth::user()->name }} 👋</h3>
            <p class="small mb-0" style="color:#8b93a1;">Student Management System - Student Portal</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm px-3" style="background:transparent; border:1px solid #4b2530; color:#f87171;">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>

    @if(!$student)
        <div class="rounded-4 p-3 shadow-sm mb-4" style="background:#2a2210; border:1px solid #4d3d16; color:#facc15;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Your student profile details were not found in the system. Please contact Admin.
        </div>
    @else
        <!-- Top Cards Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="rounded-4 shadow-sm p-3" style="background:linear-gradient(135deg,#152238 0%,#161a22 100%); border-left:4px solid #3b82f6;">
                    <small class="text-uppercase fw-bold" style="letter-spacing:.5px; color:#60a5fa;">My Batch</small>
                    <h4 class="fw-bold mb-0 mt-1" style="color:#f1f3f7;">{{ $batch->name ?? $batch->batch_name ?? 'N/A' }}</h4>
                    <small style="color:#8b93a1;">{{ $batch->course_name ?? 'Course Not Assigned' }}</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="rounded-4 shadow-sm p-3" style="background:linear-gradient(135deg,#241a38 0%,#161a22 100%); border-left:4px solid #a855f7;">
                    <small class="text-uppercase fw-bold" style="letter-spacing:.5px; color:#c084fc;">Assigned Teacher</small>
                    <h4 class="fw-bold mb-0 mt-1" style="color:#f1f3f7;">{{ $batch->teacher->name ?? 'Not Assigned' }}</h4>
                    <small style="color:#8b93a1;">{{ $batch->teacher->email ?? '' }}</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="rounded-4 shadow-sm p-3" style="background:linear-gradient(135deg,#0f2b1e 0%,#161a22 100%); border-left:4px solid #22c55e;">
                    <small class="text-uppercase fw-bold" style="letter-spacing:.5px; color:#4ade80;">Registration Info</small>
                    <h4 class="fw-bold mb-0 mt-1" style="color:#f1f3f7;">Reg No: {{ $student->reg_no ?? 'N/A' }}</h4>
                    <small style="color:#8b93a1;">Joined: {{ $student->created_at ? $student->created_at->format('Y-m-d') : 'N/A' }}</small>
                </div>
            </div>
        </div>

        <!-- Available Exams & Assignments Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="rounded-4 shadow-sm" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
                    <div class="fw-bold py-3 px-3 d-flex justify-content-between align-items-center" style="background:#1a1e27; color:#f1f3f7; border-bottom:1px solid #23262f;">
                        <span><i class="bi bi-journal-check me-2" style="color:#60a5fa;"></i>Active Exams & Assignments</span>
                        <span class="badge rounded-pill bg-primary">{{ $exams->count() }} Available</span>
                    </div>
                    <div class="p-3">
                        <div class="row g-3">
                            @forelse($exams as $exam)
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background:#0f1115; border:1px solid #2b303b;">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge text-uppercase" style="background-color: {{ $exam->type === 'mcq' ? '#1e3a8a' : '#581c87' }}; color:#ffffff;">
                                                    {{ strtoupper($exam->type) }}
                                                </span>
                                                <small class="fw-bold" style="color:#60a5fa;">{{ $exam->subject->subject_code ?? '' }}</small>
                                            </div>
                                            <h5 class="fw-bold mb-1" style="color:#f1f3f7;">{{ $exam->title }}</h5>
                                            <p class="small mb-2" style="color:#8b93a1;">Subject: {{ $exam->subject->subject_name ?? 'N/A' }}</p>
                                            <div class="small mb-3" style="color:#6b7280;">
                                                <div><i class="bi bi-award me-1"></i> Total Marks: {{ $exam->total_marks }}</div>
                                                @if($exam->end_time)
                                                    <div><i class="bi bi-clock me-1"></i> Deadline: {{ \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d H:i') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="#" class="btn btn-sm w-100 mt-2 fw-semibold" style="background-color:#2563eb; color:#ffffff; border:none;">
                                            Attempt Now
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <p class="mb-0" style="color:#6b7280;">No published exams or assignments currently available for your subjects.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Profile Details & Batch Mates Section -->
        <div class="row g-4">
            <!-- Student Profile Information -->
            <div class="col-md-4">
                <div class="rounded-4 shadow-sm h-100" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
                    <div class="fw-bold py-3 px-3" style="background:#1a1e27; color:#f1f3f7; border-bottom:1px solid #23262f;">
                        <i class="bi bi-person-badge me-2" style="color:#60a5fa;"></i>My Profile Details
                    </div>
                    <div class="p-3">
                        <div class="mb-3 pb-3" style="border-bottom:1px dashed #23262f;">
                            <label class="small d-block mb-1" style="color:#6b7280;">Full Name</label>
                            <span class="fw-semibold" style="color:#f1f3f7;">{{ $student->name }}</span>
                        </div>
                        <div class="mb-3 pb-3" style="border-bottom:1px dashed #23262f;">
                            <label class="small d-block mb-1" style="color:#6b7280;">Registration Number</label>
                            <span class="badge px-2 py-1" style="background-color:#3b82f6; color:#ffffff;">{{ $student->reg_no ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3 pb-3" style="border-bottom:1px dashed #23262f;">
                            <label class="small d-block mb-1" style="color:#6b7280;">Email Address</label>
                            <span style="color:#e5e7eb;">{{ $student->email }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="small d-block mb-1" style="color:#6b7280;">Date of Birth</label>
                            <span style="color:#e5e7eb;">{{ $student->dob ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Batch Mates List Table -->
            <div class="col-md-8">
                <div class="rounded-4 shadow-sm h-100" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
                    <div class="fw-bold py-3 px-3" style="background:#1a1e27; color:#f1f3f7; border-bottom:1px solid #23262f;">
                        <i class="bi bi-people me-2" style="color:#60a5fa;"></i>Batch Mates ({{ $batchMates->count() }})
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="--bs-table-bg:#161a22; --bs-table-hover-bg:#1c212b; --bs-table-color:#e5e7eb; --bs-table-border-color:#23262f;">
                                <thead>
                                    <tr style="background-color:#1a1e27;">
                                        <th class="ps-3 small text-uppercase fw-bold py-3" style="letter-spacing:.5px; color:#6b7280;">Reg No</th>
                                        <th class="small text-uppercase fw-bold py-3" style="letter-spacing:.5px; color:#6b7280;">Name</th>
                                        <th class="small text-uppercase fw-bold py-3" style="letter-spacing:.5px; color:#6b7280;">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($batchMates as $mate)
                                        <tr>
                                            <td class="ps-3 fw-bold" style="color:#60a5fa;">{{ $mate->reg_no ?? 'N/A' }}</td>
                                            <td style="color:#f1f3f7;">{{ $mate->name }}</td>
                                            <td style="color:#8b93a1;">{{ $mate->email }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4" style="color:#6b7280;">
                                                No other students enrolled in this batch yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection