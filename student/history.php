<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$rollno = $_SESSION['RollNo'];
$searchTerm = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $searchTerm = trim($_POST['title']);
    $s = $conn->real_escape_string($searchTerm);
    $sql = "SELECT r.BookId, b.Title, r.Date_of_Issue, r.Date_of_Return
            FROM LMS.record r
            JOIN LMS.book b ON r.BookId = b.BookId
            WHERE r.RollNo = '$rollno' AND r.Date_of_Return IS NOT NULL
            AND (r.BookId = '$s' OR b.Title LIKE '%$s%')
            ORDER BY r.Date_of_Issue DESC";
} else {
    $sql = "SELECT r.BookId, b.Title, r.Date_of_Issue, r.Date_of_Return
            FROM LMS.record r
            JOIN LMS.book b ON r.BookId = b.BookId
            WHERE r.RollNo = '$rollno' AND r.Date_of_Return IS NOT NULL
            ORDER BY r.Date_of_Issue DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrowing History - LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <?php include('sidebar.php'); ?>
  </div>
</div>

<div class="main-wrapper d-flex">
  <!-- Sidebar (Desktop) -->
  <div class="sidebar d-none d-md-block">
    <?php include('sidebar.php'); ?>
  </div>

  <!-- Content Area -->
  <div class="content-area flex-grow-1 p-4">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title mb-4">
            <i class="bi bi-clock-history me-2"></i> Borrowing History
          </h3>

          <!-- Search Form -->
          <form method="post" class="row g-3 mb-4 needs-validation" novalidate>
            <div class="col-md-9">
              <input type="text" name="title" class="form-control" placeholder="Search by Book ID or Title" value="<?= htmlspecialchars($searchTerm) ?>" required>
              <div class="invalid-feedback">Please enter a book ID or title.</div>
            </div>
            <div class="col-md-3">
              <button type="submit" name="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1"></i> Search
              </button>
            </div>
          </form>

          <!-- Result Table -->
          <?php if (!$result): ?>
            <div class="alert alert-danger">Error: <?= $conn->error ?></div>
          <?php elseif ($result->num_rows === 0): ?>
            <div class="alert alert-info text-center">
              <i class="bi bi-info-circle"></i> No records found<?= $searchTerm ? " for '<strong>" . htmlspecialchars($searchTerm) . "</strong>'" : "" ?>.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>📚 Book ID</th>
                    <th>📖 Title</th>
                    <th>📅 Issued On</th>
                    <th>🗓️ Returned On</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['BookId']) ?></td>
                      <td><?= htmlspecialchars($row['Title']) ?></td>
                      <td><?= date('d M Y', strtotime($row['Date_of_Issue'])) ?></td>
                      <td><?= date('d M Y', strtotime($row['Date_of_Return'])) ?></td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto py-3 bg-light text-center">
  <div class="container">
    <small class="text-muted">&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Bootstrap validation
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  })();
</script>

</body>
</html>
