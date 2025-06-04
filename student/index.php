<?php
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
$category = $row['Category'];
$email = $row['EmailId'];
$mobno = $row['MobNo'];

// Check dues
$due_query = "SELECT SUM(Dues) AS total_due FROM LMS.record WHERE RollNo='$rollno' AND Dues > 0";
$due_result = $conn->query($due_query);
$due_data = $due_result->fetch_assoc();
$total_due = $due_data['total_due'] ?? 0;
$has_due = $total_due > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LMS - Profile</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="css/custom-style.css" rel="stylesheet">
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

<!-- Main Content -->
<div class="container-fluid">
  <div class="row g-0">
    <nav class="col-md-3 d-none d-md-block bg-light border-end min-vh-100">
      <?php include('sidebar.php'); ?>
    </nav>

    <div class="col-12 col-md-9 mx-auto" style="padding: 40px">
      <div class="card profile-card shadow mt-3">
        <div class="card-body text-center">
          <img src="images/profile2.png" class="rounded-circle mb-3" width="120" alt="Profile Picture">
          <h3><?= htmlspecialchars($name) ?></h3>
          <p class="text-muted"><?= htmlspecialchars($category) ?></p>
          <hr>
          <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
          <p><strong>Mobile:</strong> <?= htmlspecialchars($mobno) ?></p>

          <a href="edit_student_details.php" class="btn btn-primary mt-3 me-2">
            <i class="bi bi-pencil-square me-1"></i> Edit Details
          </a>

          <a href="chatbot.php" class="btn btn-outline-success mt-3">
            🤖 Chat with LMS Bot
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Dues Modal -->
<?php if ($has_due): ?>
<div class="modal fade" id="duesModal" tabindex="-1" aria-labelledby="duesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="duesModalLabel">⚠️ Pending Dues</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>You have a total of <strong>₹<?= $total_due ?></strong> in pending dues.</p>
        <p>Please clear your dues at the earliest to avoid penalties.</p>
      </div>
      <div class="modal-footer">
        <a href="clear_dues.php" class="btn btn-danger">Clear Dues</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Auto show dues modal -->
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const duesModal = new bootstrap.Modal(document.getElementById('duesModal'));
    duesModal.show();
  });
</script>
<?php endif; ?>

<!-- Footer -->
<footer class="footer mt-auto py-3 bg-light text-center">
  <div class="container">
    <small class="text-muted">&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>

</body>
</html>
