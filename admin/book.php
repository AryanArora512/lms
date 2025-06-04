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
  <title>All Books - LMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>

<?php include('navbar.php'); ?>
<?php include('sidebar.php'); ?>

<!-- Main Content -->
<div class="content-wrapper" style="padding: 80px 20px 20px 20px;">
  <div class="container-fluid">

    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">🔍 Search Books</h5>
      </div>
      <div class="card-body">
        <form class="row g-3" method="post" action="book.php">
          <div class="col-md-10">
            <label for="title" class="form-label">Name or Book ID</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="e.g., C++ or BK1003" required>
          </div>
          <div class="col-md-2 align-self-end">
            <button type="submit" name="submit" class="btn btn-success w-100">Search</button>
          </div>
        </form>
      </div>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        $s = mysqli_real_escape_string($conn, $_POST['title']);
        $sql = "SELECT * FROM LMS.book WHERE BookId='$s' OR Title LIKE '%$s%'";
    } else {
        $sql = "SELECT * FROM LMS.book";
    }

    $result = $conn->query($sql);
    $rowcount = mysqli_num_rows($result);

    if (!$rowcount) {
        echo "<div class='alert alert-warning text-center'>No results found.</div>";
    } else {
    ?>
    <!-- Book Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        <h6 class="mb-0">📚 Book List</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Availability</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()) {
                $bookid = htmlspecialchars($row['BookId']);
                $name = htmlspecialchars($row['Title']);
                $avail = htmlspecialchars($row['Availability']);
              ?>
              <tr>
                <td><?= $bookid ?></td>
                <td><?= $name ?></td>
                <td><b><?= $avail ?></b></td>
                <td>
                  <a href="bookdetails.php?id=<?= $bookid ?>" class="btn btn-sm btn-outline-primary">Details</a>
                  <a href="edit_book_details.php?id=<?= $bookid ?>" class="btn btn-sm btn-outline-success">Edit</a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<!-- Footer -->
<div class="footer text-center mt-4">
  &copy; 2025 Library Management System — All rights reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
