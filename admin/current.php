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
  <title>LMS - Currently Issued Books</title>

  <!-- Bootstrap 5 CSS (use your version or CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <!-- Custom Styles -->
  <link href="css/custom-style.css" rel="stylesheet" />
</head>

<body>
  <?php include('navbar.php'); ?>
  <?php include('sidebar.php'); ?>

  <div class="content-wrapper container mt-5 pt-4">
    <h2 class="mb-4">📚 Currently Issued Books</h2>

    <form class="row g-3 mb-4" action="current.php" method="post">
      <div class="col-auto">
        <label for="title" class="col-form-label"><b>Search:</b></label>
      </div>
      <div class="col-auto">
        <input type="text" id="title" name="title" placeholder="Enter Roll No / Book Name / Book Id" class="form-control" required />
      </div>
      <div class="col-auto">
        <button type="submit" name="submit" class="btn btn-primary">Search</button>
      </div>
    </form>

    <div class="table-responsive">
      <?php
      $search = '';
      if (isset($_POST['submit'])) {
          $search = $conn->real_escape_string(trim($_POST['title']));
          $sql = "SELECT record.BookId, RollNo, Title, Due_Date, Date_of_Issue, DATEDIFF(CURDATE(), Due_Date) AS dues
                  FROM LMS.record
                  JOIN LMS.book ON book.BookId = record.BookId
                  WHERE Date_of_Issue IS NOT NULL AND Date_of_Return IS NULL
                    AND (RollNo = '$search' OR record.BookId = '$search' OR Title LIKE '%$search%')";
      } else {
          $sql = "SELECT record.BookId, RollNo, Title, Due_Date, Date_of_Issue, DATEDIFF(CURDATE(), Due_Date) AS dues
                  FROM LMS.record
                  JOIN LMS.book ON book.BookId = record.BookId
                  WHERE Date_of_Issue IS NOT NULL AND Date_of_Return IS NULL";
      }

      $result = $conn->query($sql);

      if (!$result || $result->num_rows == 0) {
          echo '<p class="text-center fs-4 mt-5">No Results Found.</p>';
      } else {
          echo '<table class="table table-striped table-bordered align-middle">';
          echo '<thead class="table-dark">';
          echo '<tr>';
          echo '<th>Roll No</th>';
          echo '<th>Book Id</th>';
          echo '<th>Book Name</th>';
          echo '<th>Issue Date</th>';
          echo '<th>Due Date</th>';
          echo '<th>Dues (days)</th>';
          echo '</tr>';
          echo '</thead><tbody>';

          while ($row = $result->fetch_assoc()) {
              $rollno = strtoupper(htmlspecialchars($row['RollNo']));
              $bookid = htmlspecialchars($row['BookId']);
              $title = htmlspecialchars($row['Title']);
              $issueDate = htmlspecialchars($row['Date_of_Issue']);
              $dueDate = htmlspecialchars($row['Due_Date']);
              $dues = (int)$row['dues'];

              echo '<tr>';
              echo "<td>$rollno</td>";
              echo "<td>$bookid</td>";
              echo "<td>$title</td>";
              echo "<td>$issueDate</td>";
              echo "<td>$dueDate</td>";
              echo "<td>";
              if ($dues > 0) {
                  echo "<span class='text-danger fw-bold'>$dues</span>";
              } else {
                  echo "<span class='text-success'>0</span>";
              }
              echo "</td>";
              echo '</tr>';
          }

          echo '</tbody></table>';
      }
      ?>
    </div>
  </div>

  <footer class="footer bg-light py-3 text-center mt-auto">
    &copy; 2025 Library Management System. All rights reserved.
  </footer>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
