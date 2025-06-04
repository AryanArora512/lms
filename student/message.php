<?php
require('dbconn.php');
session_start();

if (!isset($_SESSION['RollNo'])) {
    echo "<script type='text/javascript'>alert('Access Denied!!!'); window.location.href='../index.php';</script>";
    exit;
}

// Handle form submission BEFORE HTML
if (isset($_POST['submit'])) {
    $rollno = $_POST['RollNo'];
    $message = $_POST['Message'];

    $sql1 = "INSERT INTO LMS.message (RollNo, Msg, Date, Time) VALUES ('$rollno', '$message', CURDATE(), CURTIME())";

    if ($conn->query($sql1) === TRUE) {
        $_SESSION['flash_success'] = "Message sent successfully!";
    } else {
        $_SESSION['flash_error'] = "Error sending message.";
    }

    // Redirect to avoid form resubmission
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>
<div class="d-flex">
  <?php include 'sidebar.php'; ?>

  <main class="flex-grow-1 p-4">
    <div class="container">
      <!-- Flash Messages -->
      <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Message Form -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Send a Message</h5>
        </div>
        <div class="card-body">
          <form method="post" action="index.php">
            <div class="mb-3">
              <label for="RollNo" class="form-label">Receiver Roll No</label>
              <input type="text" class="form-control" id="RollNo" name="RollNo" placeholder="Enter Roll No" required>
            </div>
            <div class="mb-3">
              <label for="Message" class="form-label">Message</label>
              <input type="text" class="form-control" id="Message" name="Message" placeholder="Enter your message" required>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>

<footer class="text-center py-3 text-muted border-top mt-auto">
  &copy; 2025 Library Management System. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
