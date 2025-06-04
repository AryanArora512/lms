<?php
ob_start();
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$rollno = $_SESSION['RollNo'];
$sqlUser = "SELECT * FROM LMS.user WHERE RollNo='$rollno'";
$resultUser = $conn->query($sqlUser);
$rowUser = $resultUser->fetch_assoc();

$name = $rowUser['Name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Return Requests - Admin Dashboard - LMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="css/custom-style.css" rel="stylesheet" />
</head>

<body>
  <?php include('navbar.php'); ?>
  <?php include('sidebar.php'); ?>

  <div class="content-wrapper">
    <div class="container mt-5 pt-4">
      <h2 class="mb-4">📥 Return Requests</h2>

            <div class="d-flex gap-2 mb-4">
                <a href="issue_requests.php" class="btn btn-outline-primary">Issue Requests</a>
                <a href="renew_requests.php" class="btn btn-primary">Renew Requests</a>
                <a href="return_requests.php" class="btn btn-outline-success">Return Requests</a>
            </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Roll Number</th>
              <th>Book Id</th>
              <th>Book Name</th>
              <th>Dues</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT return.BookId, return.RollNo, Title, DATEDIFF(CURDATE(), Due_Date) AS dues 
                    FROM LMS.return 
                    JOIN LMS.book ON return.BookId = book.BookId 
                    JOIN LMS.record ON return.BookId = record.BookId AND return.RollNo = record.RollNo";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
              echo '<tr><td colspan="5" class="text-center">No return requests found.</td></tr>';
            } else {
              while ($row = $result->fetch_assoc()) {
                $bookid = htmlspecialchars($row['BookId']);
                $rollno = strtoupper(htmlspecialchars($row['RollNo']));
                $title = htmlspecialchars($row['Title']);
                $dues = (int)$row['dues'];
                if ($dues < 0) $dues = 0;
            ?>
                <tr>
                  <td><?php echo $rollno; ?></td>
                  <td><?php echo $bookid; ?></td>
                  <td><strong><?php echo $title; ?></strong></td>
                  <td><?php echo $dues; ?></td>
                  <td>
                    <a href="acceptreturn.php?id1=<?php echo urlencode($bookid); ?>&id2=<?php echo urlencode($rollno); ?>&id3=<?php echo $dues; ?>" 
                       class="btn btn-success btn-sm">
                      <i class="fas fa-check-circle"></i> Accept
                    </a>
                  </td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <footer class="footer bg-light py-3 text-center mt-auto">
    &copy; 2025 Library Management System. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
