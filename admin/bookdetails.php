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
    <title>LMS</title>

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

                <div class="span9">
                    <div class="content content-padding">

                        <div class="module">
                            <div class="module-head">
                                <h3>Book Details</h3>
                            </div>
                            <div class="module-body">
                                <?php
                                    $x = $_GET['id'];
                                    $sql = "SELECT * FROM LMS.book WHERE BookId = '$x'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();    
                                    
                                    $bookid = $row['BookId'];
                                    $name = $row['Title'];
                                    $publisher = $row['Publisher'];
                                    $year = $row['Year'];
                                    $avail = $row['Availability'];

                                    echo "<b>Book ID:</b> $bookid<br><br>";
                                    echo "<b>Title:</b> $name<br><br>";

                                    $sql1 = "SELECT * FROM LMS.author WHERE BookId = '$bookid'";
                                    $result1 = $conn->query($sql1);

                                    echo "<b>Author:</b> ";
                                    while ($row1 = $result1->fetch_assoc()) {
                                        echo $row1['Author'] . "&nbsp;";
                                    }
                                    echo "<br><br>";

                                    echo "<b>Publisher:</b> $publisher<br><br>";
                                    echo "<b>Year:</b> $year<br><br>";
                                    echo "<b>Availability:</b> $avail<br><br>";
                                ?>
                                
                                <a href="book.php" class="btn btn-primary">Go Back</a>                             
                            </div>
                        </div>

                    </div>
                </div><!--/.span9-->
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2025 Library Management System</b> All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script src="scripts/jquery-1.9.1.min.js"></script>
    <script src="scripts/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="scripts/flot/jquery.flot.js"></script>
    <script src="scripts/flot/jquery.flot.resize.js"></script>
    <script src="scripts/datatables/jquery.dataTables.js"></script>
    <script src="scripts/common.js"></script>
</body>
</html>

<?php 
} else {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
} 
?>
