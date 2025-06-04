<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo "<script>alert('Invalid Book ID'); window.location.href='book.php';</script>";
    exit();
}

$bookId = trim($_GET['id']);
$roll = $_SESSION['RollNo'];

// Get book details
$stmt = $conn->prepare("SELECT * FROM LMS.book WHERE BookId = ?");
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Book not found'); window.location.href='book.php';</script>";
    exit();
}

$book = $result->fetch_assoc();
$stmt->close();

// Get authors
$stmtAuthors = $conn->prepare("SELECT Author FROM LMS.author WHERE BookId = ?");
$stmtAuthors->bind_param("i", $bookId);
$stmtAuthors->execute();
$resultAuthors = $stmtAuthors->get_result();

// Check if user has unpaid dues
$stmtDue = $conn->prepare("SELECT SUM(Dues) as total_due FROM LMS.record WHERE RollNo = ? AND Dues > 0");
$stmtDue->bind_param("s", $roll);
$stmtDue->execute();
$resultDue = $stmtDue->get_result();
$dueData = $resultDue->fetch_assoc();
$hasDues = ($dueData['total_due'] > 0);
$stmtDue->close();

// Check if book is already issued to user
$stmtIssued = $conn->prepare("SELECT * FROM LMS.record WHERE RollNo = ? AND BookId = ? AND Date_of_Return IS NULL");
$stmtIssued->bind_param("si", $roll, $bookId);
$stmtIssued->execute();
$issuedResult = $stmtIssued->get_result();
$isIssued = $issuedResult->num_rows > 0;

// Check if book is requested
$stmtRequested = $conn->prepare("SELECT * FROM LMS.requests WHERE RollNo = ? AND BookId = ?");
$stmtRequested->bind_param("si", $roll, $bookId);
$stmtRequested->execute();
$requestResult = $stmtRequested->get_result();
$isRequested = $requestResult->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Book Details - LMS</title>

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
    <div class="messages-card">
      <h3 class="card-title mb-4"><i class="bi bi-book"></i> Book Details</h3>

      <dl class="row">
        <dt class="col-sm-3">Book ID:</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($book['BookId']) ?></dd>

        <dt class="col-sm-3">Title:</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($book['Title']) ?></dd>

        <dt class="col-sm-3">Author(s):</dt>
        <dd class="col-sm-9">
          <?php
            $authors = [];
            while ($authorRow = $resultAuthors->fetch_assoc()) {
                $authors[] = htmlspecialchars($authorRow['Author']);
            }
            echo implode(", ", $authors);
          ?>
        </dd>

        <dt class="col-sm-3">Publisher:</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($book['Publisher']) ?></dd>

        <dt class="col-sm-3">Year:</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($book['Year']) ?></dd>

        <dt class="col-sm-3">Availability:</dt>
        <dd class="col-sm-9">
          <?php if ((int)$book['Availability'] > 0): ?>
            <span class="text-success fw-bold">Available</span>
          <?php else: ?>
            <span class="text-danger fw-bold">Not Available</span>
          <?php endif; ?>
        </dd>

        <dt class="col-sm-3">Actions:</dt>
        <dd class="col-sm-9">
         <?php
if ($hasDues) {
    // Show warning button which ONLY shows alert popup (no redirect)
    echo '<button type="button" class="btn btn-warning me-2" onclick="alert(\'Please clear all dues to issue a new book.\')">
            <i class="bi bi-exclamation-circle"></i> Clear Your Dues First
          </button>';

    // Disabled Issue Request button
    echo '<button class="btn btn-success me-2" disabled>
            <i class="bi bi-journal-arrow-up"></i> Issue Request
          </button>';

    // Show Clear Due link/button so user can pay dues
    echo '<a href="clear_due.php" class="btn btn-danger me-2">
            <i class="bi bi-cash-stack"></i> Clear Due
          </a>';

} else {
    // No dues - no warning, no clear due button

    if ($isIssued) {
        echo '<button class="btn btn-secondary me-2" disabled><i class="bi bi-check-circle"></i> Already Issued</button>';
    } elseif ($isRequested) {
        echo '<a href="cancel_request.php?id=' . urlencode($bookId) . '" class="btn btn-danger me-2"><i class="bi bi-x-circle"></i> Cancel Request</a>';
    } else {
        if ((int)$book['Availability'] > 0) {
            echo '<a href="issue_request.php?id=' . urlencode($bookId) . '" class="btn btn-success me-2"><i class="bi bi-journal-arrow-up"></i> Issue Request</a>';
        } else {
            echo '<button class="btn btn-warning me-2" disabled><i class="bi bi-exclamation-circle"></i> Not Available</button>';
        }
    }
}
// Always show these buttons regardless of dues
echo '<a href="issued_books.php" class="btn btn-info me-2"><i class="bi bi-bookmark-plus"></i> Issued Books</a>';
echo '<a href="book.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> All Books</a>';
?>
        </dd>
      </dl>
    </div>
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
$stmtAuthors->close();
$conn->close();
?>
