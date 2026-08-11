@extends('app')

@push('header')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="mb-0 fs-3">Dashboard Overview</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Success Message Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Dynamic Info Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Students Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-primary text-white shadow-sm rounded-3">
                <span class="info-box-icon bg-primary-subtle text-primary rounded-start-3"><i class="bi bi-people-fill fs-2"></i></span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase fw-semibold text-white-50 small">Total Students</span>
                    <span class="info-box-number fs-3 fw-bold">{{ $totalStudents }}</span>
                </div>
            </div>
        </div>

        <!-- Active Batches Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-success text-white shadow-sm rounded-3">
                <span class="info-box-icon bg-success-subtle text-success rounded-start-3"><i class="bi bi-journal-bookmark-fill fs-2"></i></span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase fw-semibold text-white-50 small">Active Batches</span>
                    <span class="info-box-number fs-3 fw-bold">{{ $totalBatches }}</span>
                </div>
            </div>
        </div>

        <!-- New This Month Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-warning text-dark shadow-sm rounded-3">
                <span class="info-box-icon bg-warning-subtle text-warning rounded-start-3"><i class="bi bi-person-plus-fill fs-2"></i></span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase fw-semibold text-dark-50 small">New This Month</span>
                    <span class="info-box-number fs-3 fw-bold">{{ $newThisMonth }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Register Action Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-info text-white shadow-sm rounded-3">
                <span class="info-box-icon bg-info-subtle text-info rounded-start-3"><i class="bi bi-lightning-charge-fill fs-2"></i></span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase fw-semibold text-white-50 small">Quick Action</span>
                    <a href="{{ route('student.register') }}" class="btn btn-sm btn-light text-info fw-bold mt-1">
                        + Add Student
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Recent Registrations -->
    <div class="row g-4">
        <!-- Live Chart: Students per Batch -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-body-tertiary border-0 py-3">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Students per Batch
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="batchChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Live Table: Recent Registrations -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-body-tertiary border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-clock-history me-2 text-warning"></i>Recent Registrations
                    </h5>
                    <a href="{{ route('student.index') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Reg No</th>
                                    <th>Name</th>
                                    <th>Batch</th>
                                    <th>Registered Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStudents as $student)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-primary">{{ $student->reg_no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            @if($student->batch)
                                                <span class="badge bg-primary-subtle text-info border border-info-subtle px-2 py-1">
                                                    {{ $student->batch->batch_name }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-warning border border-warning-subtle px-2 py-1">
                                                    Not Assigned
                                                </span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $student->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No recent students found.</td>
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

<!-- Include Chart.js script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('batchChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($batchNames) !!},
            datasets: [{
                label: 'Number of Students',
                data: {!! json_encode($studentCounts) !!},
                backgroundColor: '#0d6efd',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endsection