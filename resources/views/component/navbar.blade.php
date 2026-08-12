<nav class="app-header navbar navbar-expand bg-body shadow-sm">
  <!--begin::Container-->
  <div class="container-fluid">
    
    <!--begin::Left Navbar Links-->
    <ul class="navbar-nav align-items-center">
      <!-- Sidebar Toggle -->
      <li class="nav-item">
        <a class="nav-link fs-5" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </a>
      </li>

      <!-- Quick Links (LMS Context) -->
      <li class="nav-item d-none d-md-block ms-2">
        <a href="{{ route('dashboard') }}" class="nav-link fw-semibold">
          <i class="bi bi-speedometer2 me-1 text-primary"></i> Dashboard
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="{{ route('student.register') }}" class="nav-link">
          <i class="bi bi-person-plus me-1 text-success"></i> New Student
        </a>
      </li>
    </ul>
    <!--end::Left Navbar Links-->

    <!--begin::Center Search Bar-->
    <form class="navbar-search d-none d-lg-block ms-auto me-auto" style="width: 300px;" role="search">
      <div class="input-group input-group-sm">
        <input type="search" class="form-control rounded-start-pill border-end-0 bg-body-tertiary" placeholder="Search students, batches..." aria-label="Search">
        <button class="btn btn-outline-secondary rounded-end-pill border-start-0 bg-body-tertiary text-body-secondary" type="submit">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </form>
    <!--end::Center Search Bar-->

    <!--begin::Right Navbar Links-->
    <ul class="navbar-nav ms-auto align-items-center">

      <!-- Notifications Dropdown -->
      <li class="nav-item dropdown me-2">
        <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" aria-expanded="false">
          <i class="bi bi-bell fs-5"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
            3
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0">
          <span class="dropdown-header fw-bold">3 New Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item d-flex align-items-center py-2">
            <i class="bi bi-person-check text-success fs-5 me-3"></i>
            <div>
              <p class="mb-0 fs-7">New student registered</p>
              <small class="text-body-secondary fs-8">5 mins ago</small>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item d-flex align-items-center py-2">
            <i class="bi bi-journal-text text-info fs-5 me-3"></i>
            <div>
              <p class="mb-0 fs-7">Batch 01 updated</p>
              <small class="text-body-secondary fs-8">1 hour ago</small>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer text-center text-primary py-2 fw-semibold fs-7">View All Notifications</a>
        </div>
      </li>

      <!-- Dark/Light Theme Toggle -->
      <li class="nav-item dropdown me-2">
        <a class="nav-link" href="#" id="bd-theme" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
          <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
          <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="bd-theme">
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center fs-7" data-bs-theme-value="light">
              <i class="bi bi-sun-fill me-2 text-warning"></i> Light
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center fs-7" data-bs-theme-value="dark">
              <i class="bi bi-moon-fill me-2 text-primary"></i> Dark
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center fs-7 active" data-bs-theme-value="auto">
              <i class="bi bi-circle-half me-2"></i> Auto
            </button>
          </li>
        </ul>
      </li>

      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center py-0" data-bs-toggle="dropdown">
          <!-- Fallback profile icon or user image -->
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 35px; height: 35px; font-weight: 600;">
            AP
          </div>
          <span class="d-none d-md-inline fw-semibold fs-7">Alexander Pierce</span>
        </a>
        
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="width: 230px;">
          <li class="px-3 py-2 text-center bg-body-tertiary rounded-top">
            <div class="fw-bold">Alexander Pierce</div>
            <small class="text-body-secondary fs-8">System Administrator</small>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          <li>
            <a href="#" class="dropdown-item fs-7 py-2">
              <i class="bi bi-person me-2 text-primary"></i> My Profile
            </a>
          </li>
          <li>
            <a href="#" class="dropdown-item fs-7 py-2">
              <i class="bi bi-gear me-2 text-secondary"></i> System Settings
            </a>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          <li>
            <a href="#" class="dropdown-item fs-7 py-2 text-danger">
              <i class="bi bi-box-arrow-right me-2"></i> Sign Out
            </a>
          </li>
        </ul>
      </li>

    </ul>
    <!--end::Right Navbar Links-->

  </div>
  <!--end::Container-->
</nav>