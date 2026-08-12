<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-text fw-light">Student Management</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Student Management Dropdown -->
                <li class="nav-item {{ request()->is('student*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Student Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('student.register') }}" class="nav-link {{ request()->routeIs('student.register') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-plus"></i>
                                <p>Student Register</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student.index') }}" class="nav-link {{ request()->routeIs('student.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>All Students List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Teacher Management Dropdown -->
              <li class="nav-item {{ request()->is('teacher*') ? 'menu-open' : '' }}">
                     <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-badge-fill"></i>
                         <p>
                        Teacher Management
                        <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                     </a>
                  <ul class="nav nav-treeview">
                <li class="nav-item">
                   <a href="{{ route('teacher.create') }}" class="nav-link {{ request()->routeIs('teacher.create') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person-plus-fill"></i>
                     <p>Teacher Register</p>
                  </a>
              </li>
              <li class="nav-item">
                 <a href="{{ route('teacher.index') }}" class="nav-link {{ request()->routeIs('teacher.    index') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-list-ul"></i>
                  <p>All Teachers List</p>
                 </a>
              </li>
           </ul>
         </li>

                <!-- Batch Management -->
                <li class="nav-item">
                    <a href="{{ route('batch.index') }}" class="nav-link {{ request()->routeIs('batch.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                        <p>Batch Management</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>