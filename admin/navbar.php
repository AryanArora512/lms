<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Library System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle"></i> <span class="d-none d-sm-inline">Account</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="index.php"><i class="fas fa-id-badge me-2"></i> Your Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
