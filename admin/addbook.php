<?php
session_start();
require('dbconn.php');
?>

<?php 
if ($_SESSION['RollNo']) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LMS - Add Book</title>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom-style.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="content-wrapper" style="padding: 80px 20px 20px 20px;">
        <div class="container">
            <div class="row">
                <?php include 'sidebar.php'; ?>

                <div class="col-md-9">
                    <div class="card shadow rounded">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add New Book</h5>
                        </div>
                        <div class="card-body">
                            <form action="addbook.php" method="post">
                                <div class="mb-3">
                                    <label for="title" class="form-label"><strong>Book Title</strong></label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><strong>Author(s)</strong></label>
                                    <input type="text" class="form-control mb-2" name="author1" placeholder="Author 1" required>
                                    
                                </div>

                                <div class="mb-3">
                                    <label for="publisher" class="form-label"><strong>Publisher</strong></label>
                                    <input type="text" class="form-control" id="publisher" name="publisher" required>
                                </div>

                                <div class="mb-3">
                                    <label for="year" class="form-label"><strong>Year</strong></label>
                                    <input type="text" class="form-control" id="year" name="year" required>
                                </div>

                                <div class="mb-3">
                                    <label for="availability" class="form-label"><strong>Number of Copies</strong></label>
                                    <input type="number" class="form-control" id="availability" name="availability" required>
                                </div>

                                <button type="submit" name="submit" class="btn btn-success">Add Book</button>
                                <a href="book.php" class="btn btn-secondary ms-2">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div><!-- col-md-9 -->
            </div>
        </div>
    </div>

    <div class="footer mt-5 bg-light py-3">
        <div class="container text-center">
            <small>&copy; 2025 Library Management System. All rights reserved.</small>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $author1 = $_POST['author1'];
        $author2 = $_POST['author2'];
        $author3 = $_POST['author3'];
        $publisher = $_POST['publisher'];
        $year = $_POST['year'];
        $availability = $_POST['availability'];

        $sql1 = "INSERT INTO LMS.book (Title, Publisher, Year, Availability) 
                 VALUES ('$title', '$publisher', '$year', '$availability')";

        if ($conn->query($sql1) === TRUE) {
            $sql2 = "SELECT MAX(BookId) AS x FROM LMS.book";
            $result = $conn->query($sql2);
            $row = $result->fetch_assoc();
            $x = $row['x'];

            $conn->query("INSERT INTO LMS.author VALUES ('$x', '$author1')");
        

            echo "<script>alert('Book added successfully!'); window.location.href='book.php';</script>";
        } else {
            echo "<script>alert('Error adding book');</script>";
        }
    }
} else {
    echo "<script>alert('Access Denied!!!');</script>";
}
?>
