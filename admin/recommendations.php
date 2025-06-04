<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    echo "<script>alert('Access Denied!!! Please login first.'); window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LMS - Book Recommendations</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <!-- Your Custom Styles -->
  <link href="css/custom-style.css" rel="stylesheet" />
</head>

<body>
  <?php include('navbar.php'); ?>
  <?php include('sidebar.php'); ?>

  <div class="content-wrapper">
    <div class="container mt-5 pt-4">
      <h2 class="mb-4">📚 Book Recommendations</h2>

      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Book Name</th>
              <th>Description</th>
              <th>Recommended By</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM LMS.recommendations";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $bookname = htmlspecialchars($row['Book_Name']);
                $description = htmlspecialchars($row['Description']);
                $rollno = strtoupper(htmlspecialchars($row['RollNo']));
                echo "<tr>
                        <td>$bookname</td>
                        <td>$description</td>
                        <td><strong>$rollno</strong></td>
                      </tr>";
              }
            } else {
              echo '<tr><td colspan="3" class="text-center">No recommendations found.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4">
        <a href="addbook.php" class="btn btn-success"><i class="fas fa-plus"></i> Add a Book</a>
      </div>
    </div>
  </div>

  <footer class="footer bg-light py-3 text-center mt-auto">
    &copy; 2025 Library Management System. All rights reserved.
  </footer>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
