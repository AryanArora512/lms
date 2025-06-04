<?php
session_start();
require('dbconn.php');

// Redirect if user is not logged in
if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$rollno = $_SESSION['RollNo'];
$success = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['Description']);

    if ($title && $description) {
        $stmt = $conn->prepare("INSERT INTO LMS.recommendations (Book_Name, Description, RollNo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $rollno);

        if ($stmt->execute()) {
            $success = "Recommendation submitted successfully!";
        } else {
            $error = "Error submitting recommendation.";
        }

        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Recommend a Book - LMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="css/custom-style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Offcanvas Sidebar for Mobile -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarMenuLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <?php include('sidebar.php'); ?>
  </div>
</div>

<div class="container-fluid">
  <div class="row g-0">

    <!-- Desktop Sidebar -->
    <nav class="col-md-3 d-none d-md-block bg-light border-end min-vh-100">
      <?php include('sidebar.php'); ?>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 col-12 px-3 px-md-5 py-4">
      <div class="card shadow messages-card">
        <div class="card-body">
          <h3 class="card-title mb-4 text-center">
            <i class="bi bi-book-half me-2"></i> Recommend a Book
          </h3>

          <!-- Feedback Messages -->
          <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= htmlspecialchars($success) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Form -->
          <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="title" class="form-label"><strong>Book Title</strong></label>
              <input type="text" name="title" id="title" class="form-control" placeholder="Enter book title" required>
            </div>
            <div class="mb-3">
              <label for="Description" class="form-label"><strong>Description</strong></label>
              <textarea name="Description" id="Description" rows="4" class="form-control" placeholder="Describe the book" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-success w-100">
              <i class="bi bi-send-fill me-1"></i> Submit Recommendation
            </button>
          </form>

        </div>
      </div>
    </main>
  </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto py-3 bg-light text-center">
  <div class="container">
    <small class="text-muted">&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>

<!-- Form Validation Script -->
<script>
  (function () {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>

</body>
</html>
