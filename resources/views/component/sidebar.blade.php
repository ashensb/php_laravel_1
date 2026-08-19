<aside class="app-sidebar shadow-sm" data-bs-theme="light" style="background:#ffffff; border-right:1px solid #eef0f4;">
    <div class="sidebar-brand" style="border-bottom:1px solid #eef0f4;">
        <a href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('student.portal') }}" class="brand-link">
            <span class="brand-text fw-bold" style="color:#1e2530;">
                <i class="bi bi-mortarboard-fill me-2" style="color:#2563eb;"></i>Student Management
            </span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-3">
            <ul class="nav sidebar-menu flex-column px-2" data-lte-toggle="treeview" role="menu" data-accordion="false">

                {{-- ADMIN MENU --}}
                @if(Auth::check() && Auth::user()->role === 'admin')

                    <!-- Dashboard -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link rounded-3 {{ request()->routeIs('dashboard') ? 'active-sm-link' : '' }}"
                           style="color:{{ request()->routeIs('dashboard') ? '#2563eb' : '#4b5563' }}; background:{{ request()->routeIs('dashboard') ? '#eaf1ff' : 'transparent' }}; font-weight:{{ request()->routeIs('dashboard') ? '600' : '500' }};">
                            <i class="nav-icon bi bi-speedometer" style="color:{{ request()->routeIs('dashboard') ? '#2563eb' : '#8a93a3' }};"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Student Management Dropdown -->
                    <li class="nav-item mb-1 {{ request()->is('student*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link rounded-3" style="color:#4b5563; font-weight:500;">
                            <i class="nav-icon bi bi-people-fill" style="color:#8a93a3;"></i>
                            <p>
                                Student Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('student.register') }}"
                                   class="nav-link rounded-3"
                                   style="color:{{ request()->routeIs('student.register') ? '#2563eb' : '#6b7280' }}; background:{{ request()->routeIs('student.register') ? '#eaf1ff' : 'transparent' }}; font-weight:{{ request()->routeIs('student.register') ? '600' : '500' }};">
                                    <i class="nav-icon bi bi-person-plus" style="color:{{ request()->routeIs('student.register') ? '#2563eb' : '#8a93a3' }};"></i>
                                    <p>Student Register</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('student.index') }}"
                                   class="nav-link rounded-3"
                                   style="color:{{ request()->routeIs('student.index') ? '#2563eb' : '#6b7280' }}; background:{{ request()->routeIs('student.index') ? '#eaf1ff' : 'transparent' }}; font-weight:{{ request()->routeIs('student.index') ? '600' : '500' }};">
                                    <i class="nav-icon bi bi-list-ul" style="color:{{ request()->routeIs('student.index') ? '#2563eb' : '#8a93a3' }};"></i>
                                    <p>All Students List</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Teacher Management Dropdown -->
                    <li class="nav-item mb-1 {{ request()->is('teacher*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link rounded-3" style="color:#4b5563; font-weight:500;">
                            <i class="nav-icon bi bi-person-badge-fill" style="color:#8a93a3;"></i>
                            <p>
                                Teacher Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('teacher.create') }}"
                                   class="nav-link rounded-3"
                                   style="color:{{ request()->routeIs('teacher.create') ? '#7c3aed' : '#6b7280' }}; background:{{ request()->routeIs('teacher.create') ? '#f3ecfd' : 'transparent' }}; font-weight:{{ request()->routeIs('teacher.create') ? '600' : '500' }};">
                                    <i class="nav-icon bi bi-person-plus-fill" style="color:{{ request()->routeIs('teacher.create') ? '#7c3aed' : '#8a93a3' }};"></i>
                                    <p>Teacher Register</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('teacher.index') }}"
                                   class="nav-link rounded-3"
                                   style="color:{{ request()->routeIs('teacher.index') ? '#7c3aed' : '#6b7280' }}; background:{{ request()->routeIs('teacher.index') ? '#f3ecfd' : 'transparent' }}; font-weight:{{ request()->routeIs('teacher.index') ? '600' : '500' }};">
                                    <i class="nav-icon bi bi-list-ul" style="color:{{ request()->routeIs('teacher.index') ? '#7c3aed' : '#8a93a3' }};"></i>
                                    <p>All Teachers List</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Batch Management -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('batch.index') }}"
                           class="nav-link rounded-3"
                           style="color:{{ request()->routeIs('batch.*') ? '#16a34a' : '#4b5563' }}; background:{{ request()->routeIs('batch.*') ? '#e8f8ee' : 'transparent' }}; font-weight:{{ request()->routeIs('batch.*') ? '600' : '500' }};">
                            <i class="nav-icon bi bi-journal-bookmark-fill" style="color:{{ request()->routeIs('batch.*') ? '#16a34a' : '#8a93a3' }};"></i>
                            <p>Batch Management</p>
                        </a>
                    </li>

                    <!-- Assign Subjects -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.subject-teacher.index') }}"
                           class="nav-link rounded-3"
                           style="color:{{ request()->routeIs('admin.subject-teacher.*') ? '#ea580c' : '#4b5563' }}; background:{{ request()->routeIs('admin.subject-teacher.*') ? '#fff7ed' : 'transparent' }}; font-weight:{{ request()->routeIs('admin.subject-teacher.*') ? '600' : '500' }};">
                            <i class="nav-icon bi bi-book-half" style="color:{{ request()->routeIs('admin.subject-teacher.*') ? '#ea580c' : '#8a93a3' }};"></i>
                            <p>Assign Subjects</p>
                        </a>
                    </li>

                {{-- STUDENT MENU --}}
                @elseif(Auth::check() && Auth::user()->role === 'student')

                    <li class="nav-item mb-1">
                        <a href="{{ route('student.portal') }}"
                           class="nav-link rounded-3"
                           style="color:{{ request()->routeIs('student.portal') ? '#2563eb' : '#4b5563' }}; background:{{ request()->routeIs('student.portal') ? '#eaf1ff' : 'transparent' }}; font-weight:{{ request()->routeIs('student.portal') ? '600' : '500' }};">
                            <i class="nav-icon bi bi-speedometer" style="color:{{ request()->routeIs('student.portal') ? '#2563eb' : '#8a93a3' }};"></i>
                            <p>My Dashboard</p>
                        </a>
                    </li>

                {{-- TEACHER MENU --}}
                @elseif(Auth::check() && Auth::user()->role === 'teacher')

                    <li class="nav-item mb-1">
                        <a href="{{ route('teacher.dashboard') }}"
                           class="nav-link rounded-3"
                           style="color:{{ request()->routeIs('teacher.dashboard') ? '#2563eb' : '#4b5563' }}; background:{{ request()->routeIs('teacher.dashboard') ? '#eaf1ff' : 'transparent' }}; font-weight:{{ request()->routeIs('teacher.dashboard') ? '600' : '500' }};">
                            <i class="nav-icon bi bi-speedometer2" style="color:{{ request()->routeIs('teacher.dashboard') ? '#2563eb' : '#8a93a3' }};"></i>
                            <p>Teacher Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a href="#" class="nav-link rounded-3" style="color:#4b5563; font-weight:500;">
                            <i class="nav-icon bi bi-journal-bookmark-fill" style="color:#8a93a3;"></i>
                            <p>My Batches</p>
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a href="#" class="nav-link rounded-3" style="color:#4b5563; font-weight:500;">
                            <i class="nav-icon bi bi-people-fill" style="color:#8a93a3;"></i>
                            <p>My Students</p>
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a href="#" class="nav-link rounded-3" style="color:#4b5563; font-weight:500;">
                            <i class="nav-icon bi bi-check2-square" style="color:#8a93a3;"></i>
                            <p>Attendance</p>
                        </a>
                    </li>

                @endif

            </ul>
        </nav>
    </div>
</aside>