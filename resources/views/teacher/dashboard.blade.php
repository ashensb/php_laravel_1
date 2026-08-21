@extends('component.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color:#0f1115; min-height:100vh;">

    <!-- Welcome Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-4 rounded-4 shadow-sm" style="background:#161a22; border-left:4px solid #3b82f6;">
        <div>
            <h3 class="fw-bold mb-1" style="color:#f1f3f7;">Welcome, {{ Auth::user()->name }} 👋</h3>
            <p class="mb-0" style="color:#8b93a1;">Teacher Dashboard - Student Management System</p>
        </div>
        <div>
            <span class="badge px-3 py-2 fs-6" style="background-color:#3b82f6; color:#ffffff;">Teacher Portal</span>
        </div>
    </div>

    <!-- Overview Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="rounded-4 shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#152238 0%,#161a22 100%); border-left:4px solid #3b82f6;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="letter-spacing:.5px; color:#60a5fa;">Assigned Batches</h6>
                        <h2 class="fw-bold mb-0" style="color:#f1f3f7;">{{ str_pad($assignedBatchesCount, 2, '0', STR_PAD_LEFT) }}</h2>
                    </div>
                    <i class="bi bi-journal-bookmark fs-1" style="color:#3b82f6; opacity:.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rounded-4 shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#241a38 0%,#161a22 100%); border-left:4px solid #a855f7;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="letter-spacing:.5px; color:#c084fc;">Total Students</h6>
                        <h2 class="fw-bold mb-0" style="color:#f1f3f7;">{{ $totalStudentsCount }}</h2>
                    </div>
                    <i class="bi bi-people fs-1" style="color:#a855f7; opacity:.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rounded-4 shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#0f2b1e 0%,#161a22 100%); border-left:4px solid #22c55e;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1" style="letter-spacing:.5px; color:#4ade80;">Active Modules</h6>
                        <h2 class="fw-bold mb-0" style="color:#f1f3f7;">{{ str_pad($activeModulesCount, 2, '0', STR_PAD_LEFT) }}</h2>
                    </div>
                    <i class="bi bi-book fs-1" style="color:#22c55e; opacity:.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row g-3">
        <!-- Assigned Batches Table -->
        <div class="col-lg-7">
            <div class="rounded-4 shadow-sm h-100" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
                <div class="fw-bold py-3 px-3" style="background:#1a1e27; color:#f1f3f7; border-bottom:1px solid #23262f;">
                    <i class="bi bi-journal-check me-2" style="color:#60a5fa;"></i> My Assigned Batches
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="--bs-table-bg:#161a22; --bs-table-hover-bg:#1c212b; --bs-table-color:#e5e7eb; --bs-table-border-color:#23262f;">
                            <thead>
                                <tr style="background-color:#1a1e27;">
                                    <th class="ps-3 small text-uppercase fw-bold py-3" style="letter-spacing:.5px; color:#6b7280;">Batch Name</th>
                                    <th class="small text-uppercase fw-bold py-3" style="letter-spacing:.5px; color:#6b7280;">Course / Subject</th>
                                    <th class="small text-uppercase fw-bold py-3" style="letter-spacing:.5px; color:#6b7280;">Students</th>
                                    <th class="small text-uppercase fw-bold py-3 pe-3" style="letter-spacing:.5px; color:#6b7280;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batches as $batch)
                                    <tr>
                                        <td class="ps-3 fw-semibold" style="color:#f1f3f7;">{{ $batch->name ?? $batch->batch_name }}</td>
                                        <td style="color:#8b93a1;">{{ $batch->course_name ?? 'General Course' }}</td>
                                        <td><span class="badge" style="background-color:#23262f; color:#c7ccd6;">{{ $batch->students_count ?? 0 }}</span></td>
                                        <td class="pe-3">
                                            <a href="#" class="btn btn-sm" style="border:1px solid #3b82f6; color:#60a5fa; background:transparent;">View Students</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4" style="color:#8b93a1;">No assigned batches found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Profile / Info -->
        <div class="col-lg-5">
            <div class="rounded-4 shadow-sm h-100" style="background:#161a22; border:1px solid #23262f; overflow:hidden;">
                <div class="fw-bold py-3 px-3" style="background:#1a1e27; color:#f1f3f7; border-bottom:1px solid #23262f;">
                    <i class="bi bi-person-badge me-2" style="color:#60a5fa;"></i> Profile Information
                </div>
                <div class="p-3">
                    <div class="mb-3 pb-3" style="border-bottom:1px dashed #23262f;">
                        <small class="d-block mb-1" style="color:#6b7280;">Full Name</small>
                        <span class="fw-bold fs-6" style="color:#f1f3f7;">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="mb-3 pb-3" style="border-bottom:1px dashed #23262f;">
                        <small class="d-block mb-1" style="color:#6b7280;">Email Address</small>
                        <span class="fw-semibold" style="color:#e5e7eb;">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="mb-0">
                        <small class="d-block mb-1" style="color:#6b7280;">Role</small>
                        <span class="badge text-capitalize px-2 py-1" style="background-color:#0891b2; color:#ecfeff;">{{ Auth::user()->role ?? 'Teacher' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection