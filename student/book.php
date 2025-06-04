<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$roll = $_SESSION['RollNo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Books Catalog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="css/custom-style.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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

<div class="d-flex">
  <div class="sidebar d-none d-md-block">
    <?php include('sidebar.php'); ?>
  </div>

  <div class="content flex-grow-1 p-4">
    <div class="container">
      <h2 class="mb-4 text-center">📚 Books Catalog</h2>

      <form method="post" class="row g-3 mb-4">
        <div class="col-md-8">
          <input type="text" name="title" class="form-control" placeholder="Enter Book Title or ID" />
        </div>
        <div class="col-md-4">
          <button type="submit" name="submit" class="btn btn-primary w-100">Search</button>
        </div>
      </form>

      <?php
      if (isset($_POST['submit']) && !empty(trim($_POST['title']))) {
          $s = $conn->real_escape_string(trim($_POST['title']));
          $sql = "SELECT * FROM LMS.book WHERE BookId='$s' OR Title LIKE '%$s%'";
      } else {
          $sql = "SELECT * FROM LMS.book ORDER BY Availability DESC";
      }

      $result = $conn->query($sql);

      if (!$result) {
          echo "<div class='alert alert-danger'>SQL Error: " . $conn->error . "</div>";
      } elseif ($result->num_rows == 0) {
          echo "<div class='alert alert-warning'>No books found.</div>";
      } else {
          // Check for dues
          $hasDues = false;
          $duesCheck = $conn->prepare("SELECT 1 FROM LMS.record WHERE RollNo = ? AND Dues > 0 LIMIT 1");
          $duesCheck->bind_param("s", $roll);
          $duesCheck->execute();
          $duesCheck->store_result();
          if ($duesCheck->num_rows > 0) $hasDues = true;
          $duesCheck->close();

          echo '<div class="row g-3">';
          while ($row = $result->fetch_assoc()):
              $bookid = $row['BookId'];
              $title = $row['Title'];
              $avail = $row['Availability'];

              // Check if already issued
              $isIssued = false;
              $q1 = $conn->prepare("SELECT 1 FROM LMS.record WHERE RollNo = ? AND BookId = ? AND Date_of_Return IS NULL LIMIT 1");
              $q1->bind_param("si", $roll, $bookid);
              $q1->execute();
              $q1->store_result();
              if ($q1->num_rows > 0) $isIssued = true;
              $q1->close();

              // Check if requested
              $hasRequested = false;
              $q2 = $conn->prepare("SELECT 1 FROM LMS.requests WHERE RollNo = ? AND BookId = ? LIMIT 1");
              $q2->bind_param("si", $roll, $bookid);
              $q2->execute();
              $q2->store_result();
              if ($q2->num_rows > 0) $hasRequested = true;
              $q2->close();
      ?>

      <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($title) ?></h5>
            <h6 class="card-subtitle mb-2 text-muted">Book ID: <?= $bookid ?></h6>
            <p>
              <?= $avail > 0
                  ? '<span class="badge bg-success">Available</span>'
                  : '<span class="badge bg-danger">Not Available</span>' ?>
            </p>

            <div class="d-flex flex-wrap gap-2 mt-auto">
              <a href="bookdetails.php?id=<?= $bookid ?>" class="btn btn-info btn-sm">Details</a>

              <?php if ($isIssued): ?>
                <button class="btn btn-secondary btn-sm" disabled>Issued</button>
              <?php elseif ($hasRequested): ?>
                <a href="cancel_request.php?id=<?= $bookid ?>" class="btn btn-warning btn-sm" onclick="return confirm('Cancel your request?');">Cancel Request</a>
              <?php elseif ($avail > 0): ?>
                <?php if ($hasDues): ?>
                  <button class="btn btn-danger btn-sm" onclick="alert('You have pending dues. Please clear them before issuing new books.');">Clear Dues</button>
                <?php else: ?>
                  <a href="issue_request.php?id=<?= $bookid ?>" class="btn btn-success btn-sm">Issue</a>
                <?php endif; ?>
              <?php else: ?>
                <button class="btn btn-outline-secondary btn-sm" disabled>Unavailable</button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <?php endwhile; echo '</div>'; } ?>
    </div>
  </div>
</div>

<footer class="footer mt-auto py-3 bg-light text-center">
  <div class="container">
    <small class="text-muted">&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>

</body>
</html>
