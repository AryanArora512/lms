<?php
require('dbconn.php');
session_start();

if (!isset($_SESSION['RollNo'])) {
    echo "<script>alert('Access Denied!'); window.location.href = 'login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - LMS</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>

<!-- Include Navbar and Sidebar -->
<?php include('navbar.php'); ?>
<?php include('sidebar.php'); ?>

<!-- Main Content -->
<div class="content-wrapper" style="margin-left: 250px; padding: 80px 20px 20px;">
  <div class="container-fluid">
    <h3 class="mb-4">📋 Admin Dashboard</h3>
    <div class="row text-center mb-4">
      <div class="col-md-4 mb-3">
        <a href="issue_requests.php" class="btn btn-outline-primary w-100 p-4 shadow-sm">
          <img src="images/book2.png" width="70" alt="Issue Requests" />
          <h5 class="mt-3">Issue Requests</h5>
        </a>
      </div>
      <div class="col-md-4 mb-3">
        <a href="renew_requests.php" class="btn btn-outline-success w-100 p-4 shadow-sm">
          <img src="images/book3.png" width="70" alt="Renew Requests" />
          <h5 class="mt-3">Renew Requests</h5>
        </a>
      </div>
      <div class="col-md-4 mb-3">
        <a href="return_requests.php" class="btn btn-outline-warning w-100 p-4 shadow-sm">
          <img src="images/book4.png" width="70" alt="Return Requests" />
          <h5 class="mt-3">Return Requests</h5>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer bg-light text-center py-3 mt-auto">
  <div class="container">
    <small>&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
