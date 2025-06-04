<?php
ob_start();
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$rollno = $_SESSION['RollNo'];
$sql = "SELECT * FROM LMS.user WHERE RollNo='$rollno'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$name = $row['Name'];
$email = $row['EmailId'];
$mobno = $row['MobNo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - LMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>
  <?php include('navbar.php'); ?>
  <?php include('sidebar.php'); ?>

  <div class="content-wrapper">
    <div class="container mt-5 pt-4">
      <div class="text-center mb-4">
        <img src="images/profile2.png" class="rounded-circle shadow" width="120" height="120" alt="Admin Profile">
        <h2 class="mt-3">Welcome, <?php echo htmlspecialchars($name); ?></h2>
        <p class="text-muted">Administrator Panel</p>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card text-center h-100">
            <div class="card-body">
              <i class="bi bi-people-fill fs-2 text-primary"></i>
              <h5 class="card-title mt-2">Manage Students</h5>
              <a href="student.php" class="btn btn-outline-primary btn-sm mt-2">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center h-100">
            <div class="card-body">
              <i class="bi bi-journal-bookmark-fill fs-2 text-success"></i>
              <h5 class="card-title mt-2">All Books</h5>
              <a href="book.php" class="btn btn-outline-success btn-sm mt-2">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center h-100">
            <div class="card-body">
              <i class="bi bi-envelope-fill fs-2 text-warning"></i>
              <h5 class="card-title mt-2">Messages</h5>
              <a href="message.php" class="btn btn-outline-warning btn-sm mt-2">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center h-100">
            <div class="card-body">
              <i class="bi bi-bookmark-plus-fill fs-2 text-danger"></i>
              <h5 class="card-title mt-2">Add Book</h5>
              <a href="addbook.php" class="btn btn-outline-danger btn-sm mt-2">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center h-100">
            <div class="card-body">
              <i class="bi bi-arrow-left-right fs-2 text-info"></i>
              <h5 class="card-title mt-2">Issue/Return</h5>
              <a href="requests.php" class="btn btn-outline-info btn-sm mt-2">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center h-100">
            <div class="card-body">
              <i class="bi bi-stars fs-2 text-secondary"></i>
              <h5 class="card-title mt-2">Recommendations</h5>
              <a href="recommendations.php" class="btn btn-outline-secondary btn-sm mt-2">Go</a>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <a href="edit_admin_details.php" class="btn btn-primary">Edit Admin Details</a>
      </div>
    </div>
  </div>

  <footer class="footer">
    &copy; 2025 Library Management System. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
