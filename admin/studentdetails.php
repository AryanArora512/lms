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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                <h3>Student Details</h3>
                            </div>
                            <div class="module-body">
                                <?php
                                    $rno = $_GET['id'];
                                    $sql = "SELECT * FROM LMS.user WHERE RollNo = '$rno'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();

                                    echo "<b><u>Name:</u></b> {$row['Name']}<br><br>";
                                    echo "<b><u>Category:</u></b> {$row['Category']}<br><br>";
                                    echo "<b><u>Roll No:</u></b> $rno<br><br>";
                                    echo "<b><u>Email Id:</u></b> {$row['EmailId']}<br><br>";
                                    echo "<b><u>Mobile No:</u></b> {$row['MobNo']}<br><br>";
                                ?>
                                <a href="student.php" class="btn btn-primary">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div> <!--/.span9-->
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

<?php } else {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
} ?>
