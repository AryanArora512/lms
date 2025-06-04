<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container-fluid">
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <i class="fas fa-bars"></i>
    </button>

    <a class="navbar-brand" href="#">Library System</a>

    <div class="ms-auto">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle fa-lg"></i> <span class="d-none d-sm-inline">Account</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="index.php"><i class="fas fa-id-badge me-2"></i> Your Profile</a></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
