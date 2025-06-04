<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$roll = $_SESSION['RollNo'];

// Fetch issued books details for this student
$sql = "SELECT r.BookId, b.Title, b.Publisher, b.Year, r.Date_of_Issue, r.Due_Date
        FROM LMS.record r
        JOIN LMS.book b ON r.BookId = b.BookId
        WHERE r.RollNo = ? AND r.Date_of_Return IS NULL
        ORDER BY r.Date_of_Issue DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $roll);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Issued Books - LMS</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="css/custom-style.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<?php include('navbar.php'); ?>

<div class="main-wrapper d-flex">

  <!-- Desktop Sidebar -->
  <div class="sidebar-desktop d-none d-md-block bg-light border-end">
    <?php include('sidebar.php'); ?>
  </div>

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

  <!-- Main content -->
  <main class="content-area flex-grow-1 p-4">
    <h3 class="mb-4"><i class="bi bi-bookmark-check"></i> Issued Books</h3>

    <?php if ($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>Book ID</th>
              <th>Title</th>
              <th>Publisher</th>
              <th>Year</th>
              <th>Date of Issue</th>
              <th>Due Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['BookId']) ?></td>
                <td><?= htmlspecialchars($row['Title']) ?></td>
                <td><?= htmlspecialchars($row['Publisher']) ?></td>
                <td><?= htmlspecialchars($row['Year']) ?></td>
                <td><?= htmlspecialchars($row['Date_of_Issue']) ?></td>
                <td><?= htmlspecialchars($row['Due_Date']) ?></td>
                <td>
                  <a href="bookdetails.php?id=<?= urlencode($row['BookId']) ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-info-circle"></i> Details
                  </a>
                  <!-- Optionally, add a 'Return Book' button or other actions here -->
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">
        You currently have no books issued.
      </div>
    <?php endif; ?>

    <a href="book.php" class="btn btn-secondary mt-3">
      <i class="bi bi-arrow-left"></i> Back to All Books
    </a>
  </main>
</div>

</body>
<footer class="footer mt-auto py-3 bg-light text-center">
  <div class="container">
    <small class="text-muted">&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>
</html>

<?php
$stmt->close();
$conn->close();
?>
