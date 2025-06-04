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
    <title>LMS - Edit Book</title>

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
                            <h5 class="mb-0">Update Book Details</h5>
                        </div>
                        <div class="card-body">

                            <?php
                                $bookid = $_GET['id'];
                                $sql = "SELECT * FROM LMS.book WHERE BookId = '$bookid'";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();

                                $name = $row['Title'];
                                $publisher = $row['Publisher'];
                                $year = $row['Year'];
                                $avail = $row['Availability'];
                            ?>

                            <form action="edit_book_details.php?id=<?php echo $bookid ?>" method="post">
                                <div class="mb-3">
                                    <label for="Title" class="form-label"><strong>Book Title</strong></label>
                                    <input type="text" class="form-control" id="Title" name="Title" value="<?php echo $name ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Publisher" class="form-label"><strong>Publisher</strong></label>
                                    <input type="text" class="form-control" id="Publisher" name="Publisher" value="<?php echo $publisher ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Year" class="form-label"><strong>Year</strong></label>
                                    <input type="text" class="form-control" id="Year" name="Year" value="<?php echo $year ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Availability" class="form-label"><strong>Availability</strong></label>
                                    <input type="number" class="form-control" id="Availability" name="Availability" value="<?php echo $avail ?>" required>
                                </div>

                                <button type="submit" name="submit" class="btn btn-success">Update Details</button>
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
        $bookid = $_GET['id'];
        $name = $_POST['Title'];
        $publisher = $_POST['Publisher'];
        $year = $_POST['Year'];
        $avail = $_POST['Availability'];

        $sql1 = "UPDATE LMS.book SET Title='$name', Publisher='$publisher', Year='$year', Availability='$avail' WHERE BookId='$bookid'";

        if ($conn->query($sql1) === TRUE) {
            echo "<script>alert('Book details updated successfully!');</script>";
            echo "<script>window.location.href='book.php';</script>";
        } else {
            echo "<script>alert('Error updating book details');</script>";
        }
    }
} else {
    echo "<script>alert('Access Denied!!!')</script>";
}
?>
