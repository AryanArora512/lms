<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    echo "<script>alert('Access Denied! Please login.');window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Renew Requests - LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php include('navbar.php'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include('sidebar.php'); ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 content-wrapper" style="margin-left: 250px; padding: 80px 20px 20px;">
            <h2 class="mb-4">🔁 Renew Requests</h2>

            <div class="d-flex gap-2 mb-4">
                <a href="issue_requests.php" class="btn btn-outline-primary">Issue Requests</a>
                <a href="renew_requests.php" class="btn btn-primary">Renew Requests</a>
                <a href="return_requests.php" class="btn btn-outline-success">Return Requests</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Roll Number</th>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Renewals Left</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM LMS.record, LMS.book, LMS.renew 
                                WHERE renew.BookId = book.BookId 
                                AND renew.RollNo = record.RollNo 
                                AND renew.BookId = record.BookId";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $bookid = $row['BookId'];
                                $rollno = strtoupper($row['RollNo']);
                                $name = $row['Title'];
                                $renewals = $row['Renewals_left'];
                                ?>
                                <tr>
                                    <td><?= $rollno ?></td>
                                    <td><?= $bookid ?></td>
                                    <td><strong><?= htmlspecialchars($name) ?></strong></td>
                                    <td><?= $renewals ?></td>
                                    <td class="text-center">
                                        <?php if ($renewals > 0): ?>
                                            <a href="acceptrenewal.php?id1=<?= $bookid ?>&id2=<?= $rollno ?>" class="btn btn-sm btn-success">Accept</a>
                                        <?php else: ?>
                                            <span class="text-muted">No renewals left</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No renew requests found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Footer -->
<footer class="text-center py-3 bg-light mt-auto">
    &copy; 2025 Library Management System - All rights reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
