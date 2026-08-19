@extends('component.app')

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
<style>
    :root {
        --db-bg: #0f1420;
        --db-surface: #161c2b;
        --db-surface-2: #1c2333;
        --db-border: #2a3245;
        --db-text: #e6e9f0;
        --db-text-muted: #8891a5;
        --db-accent-blue: #3b82f6;
        --db-accent-violet: #8b5cf6;
        --db-accent-green: #10b981;
        --db-accent-amber: #f59e0b;
    }

    [data-bs-theme="dark"] .app-content, [data-bs-theme="dark"] body {
        background-color: var(--db-bg);
    }

    /* Stat cards - flat surface with a colored left accent + icon chip, no loud gradients */
    .stat-card {
        background: var(--db-surface);
        border: 1px solid var(--db-border);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        position: relative;
        overflow: hidden;
        transition: border-color .15s ease, transform .15s ease;
    }
    .stat-card:hover { border-color: var(--db-accent-blue); transform: translateY(-2px); }
    .stat-card::before {
        content: "";
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
    }
    .stat-card.accent-blue::before { background: var(--db-accent-blue); }
    .stat-card.accent-violet::before { background: var(--db-accent-violet); }
    .stat-card.accent-green::before { background: var(--db-accent-green); }
    .stat-card.accent-amber::before { background: var(--db-accent-amber); }

    .stat-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    .accent-blue .stat-icon { background: rgba(59,130,246,.12); color: var(--db-accent-blue); }
    .accent-violet .stat-icon { background: rgba(139,92,246,.12); color: var(--db-accent-violet); }
    .accent-green .stat-icon { background: rgba(16,185,129,.12); color: var(--db-accent-green); }
    .accent-amber .stat-icon { background: rgba(245,158,11,.12); color: var(--db-accent-amber); }

    .stat-label {
        font-size: .72rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--db-text-muted);
        font-weight: 600;
        margin-bottom: .15rem;
        display: block;
    }
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--db-text);
        line-height: 1;
    }

    .quick-action-btn {
        border-radius: .5rem;
        font-weight: 600;
        font-size: .8rem;
        padding: .4rem .7rem;
        border: 1px solid var(--db-border);
    }
    .quick-action-btn.primary-action {
        background: var(--db-accent-blue);
        color: #fff;
        border-color: var(--db-accent-blue);
    }
    .quick-action-btn.secondary-action {
        background: transparent;
        color: var(--db-text);
    }
    .quick-action-btn.secondary-action:hover { background: var(--db-surface-2); color: var(--db-text); }

    /* Panels */
    .panel-card {
        background: var(--db-surface);
        border: 1px solid var(--db-border);
        border-radius: .75rem;
    }
    .panel-header {
        background: transparent;
        border-bottom: 1px solid var(--db-border);
        padding: 1rem 1.25rem;
    }
    .panel-title {
        color: var(--db-text);
        font-weight: 600;
        font-size: .95rem;
        margin: 0;
    }
    .panel-title i { color: var(--db-accent-blue); }

    .panel-table thead th {
        background: var(--db-surface-2);
        color: var(--db-text-muted);
        font-size: .7rem;
        letter-spacing: .05em;
        text-transform: uppercase;
        font-weight: 600;
        border-bottom: 1px solid var(--db-border);
        padding: .7rem 1rem;
    }
    .panel-table tbody td {
        color: var(--db-text);
        border-bottom: 1px solid var(--db-border);
        padding: .65rem 1rem;
        font-size: .875rem;
    }
    .panel-table tbody tr:last-child td { border-bottom: none; }
    .panel-table tbody tr:hover { background: var(--db-surface-2); }

    .badge-soft {
        font-weight: 600;
        font-size: .72rem;
        padding: .3rem .55rem;
        border-radius: .4rem;
    }
    .badge-soft-blue { background: rgba(59,130,246,.12); color: #7fb3ff; border: 1px solid rgba(59,130,246,.25); }
    .badge-soft-amber { background: rgba(245,158,11,.12); color: #f5c169; border: 1px solid rgba(245,158,11,.25); }
    .badge-soft-violet { background: rgba(139,92,246,.12); color: #b39dfb; border: 1px solid rgba(139,92,246,.25); }

    .link-muted { color: var(--db-accent-blue); font-size: .82rem; font-weight: 500; }
    .link-muted:hover { color: #fff; }
</style>

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
            <div class="stat-card accent-blue">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <span class="stat-label">Total Students</span>
                    <span class="stat-value">{{ $totalStudents }}</span>
                </div>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card accent-violet">
                <div class="stat-icon"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <span class="stat-label">Total Teachers</span>
                    <span class="stat-value">{{ $totalTeachers }}</span>
                </div>
            </div>
        </div>

        <!-- Active Batches Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card accent-green">
                <div class="stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                <div>
                    <span class="stat-label">Active Batches</span>
                    <span class="stat-value">{{ $totalBatches }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Register Action Card -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card accent-amber">
                <div class="stat-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <div class="flex-grow-1">
                    <span class="stat-label">Quick Actions</span>
                    <div class="d-flex gap-2 mt-1">
                        <a href="{{ route('student.register') }}" class="quick-action-btn primary-action">+ Student</a>
                        <a href="{{ route('teacher.create') }}" class="quick-action-btn secondary-action">+ Teacher</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Recent Tables -->
    <div class="row g-4 mb-4">
        <!-- Live Chart: Students per Batch -->
        <div class="col-md-6">
            <div class="panel-card h-100">
                <div class="panel-header">
                    <h5 class="panel-title">
                        <i class="bi bi-bar-chart-fill me-2"></i>Students per Batch
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="batchChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Live Table: Recent Student Registrations -->
        <div class="col-md-6">
            <div class="panel-card h-100">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5 class="panel-title">
                        <i class="bi bi-clock-history me-2"></i>Recent Students
                    </h5>
                    <a href="{{ route('student.index') }}" class="link-muted text-decoration-none">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table panel-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Reg No</th>
                                    <th>Name</th>
                                    <th>Batch</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStudents as $student)
                                    <tr>
                                        <td class="fw-semibold" style="color: var(--db-accent-blue);">{{ $student->reg_no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            @if($student->batch)
                                                <span class="badge-soft badge-soft-blue">
                                                    {{ $student->batch->name ?? $student->batch->batch_name }}
                                                </span>
                                            @else
                                                <span class="badge-soft badge-soft-amber">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td class="small" style="color: var(--db-text-muted);">{{ $student->created_at ? $student->created_at->format('Y-m-d') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4" style="color: var(--db-text-muted);">No recent students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Joined Teachers Section -->
    <div class="row g-4">
        <div class="col-12">
            <div class="panel-card">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5 class="panel-title">
                        <i class="bi bi-person-badge me-2"></i>Recently Joined Teachers
                    </h5>
                    <a href="{{ route('teacher.index') }}" class="link-muted text-decoration-none">View All Teachers</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table panel-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Qualification</th>
                                    <th>Joined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTeachers as $teacher)
                                    <tr>
                                        <td class="fw-semibold">{{ $teacher->name }}</td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>
                                          <span class="badge-soft badge-soft-violet">
                                              {{ $teacher->qualification ?? 'Lecturer' }}
                                           </span>
                                        </td>
                                        <td class="small" style="color: var(--db-text-muted);">{{ $teacher->created_at ? $teacher->created_at->format('Y-m-d') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4" style="color: var(--db-text-muted);">No teachers registered yet.</td>
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

<!-- Theme Switcher Script -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const storedTheme = localStorage.getItem('theme');

    const getPreferredTheme = () => {
      if (storedTheme) {
        return storedTheme;
      }
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const setTheme = function (theme) {
      if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
      } else if (theme === 'auto') {
        document.documentElement.setAttribute('data-bs-theme', 'light');
      } else {
        document.documentElement.setAttribute('data-bs-theme', theme);
      }
    };

    setTheme(getPreferredTheme());

    const showActiveTheme = (theme) => {
      const themeSwitcher = document.querySelector('#bd-theme');
      if (!themeSwitcher) return;

      const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);

      document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
        element.classList.remove('active');
        element.setAttribute('aria-pressed', 'false');
      });

      if (btnToActive) {
        btnToActive.classList.add('active');
        btnToActive.setAttribute('aria-pressed', 'true');
      }
    };

    showActiveTheme(getPreferredTheme());

    document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
      toggle.addEventListener('click', () => {
        const theme = toggle.getAttribute('data-bs-theme-value');
        localStorage.setItem('theme', theme);
        setTheme(theme);
        showActiveTheme(theme);
      });
    });
  });
</script>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('batchChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($batchNames) !!},
                datasets: [{
                    label: 'Number of Students',
                    data: {!! json_encode($studentCounts) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                    maxBarThickness: 46
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#8891a5' } }
                },
                scales: {
                    x: {
                        ticks: { color: '#8891a5' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#8891a5' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });
    });
</script>
@endsection