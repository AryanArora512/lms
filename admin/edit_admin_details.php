<?php
ob_start();
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: login.php");
    exit();
}

$rollno = $_SESSION['RollNo'];
$error = '';

if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['Name']);
    $email = $conn->real_escape_string($_POST['EmailId']);
    $mobno = $conn->real_escape_string($_POST['MobNo']);
    $pswd = $conn->real_escape_string($_POST['Password']);

    $sql1 = "UPDATE LMS.user SET Name='$name', EmailId='$email', MobNo='$mobno', Password='$pswd' WHERE RollNo='$rollno'";

    if ($conn->query($sql1) === TRUE) {
        echo "<script>alert('Details Updated Successfully');</script>";
        header("Refresh:0.5; url=index.php");
        ob_end_flush();
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT * FROM LMS.user WHERE RollNo='$rollno'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$name = htmlspecialchars($row['Name']);
$email = htmlspecialchars($row['EmailId']);
$mobno = htmlspecialchars($row['MobNo']);
$pswd = htmlspecialchars($row['Password']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Details - LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap and Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php include('sidebar.php'); ?>

    <div class="content-wrapper">
        <div class="container mt-5 pt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">Update Your Details</h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <form method="post">
                                <div class="mb-3">
                                    <label for="Name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="Name" name="Name" value="<?php echo $name; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="EmailId" class="form-label">Email ID</label>
                                    <input type="email" class="form-control" id="EmailId" name="EmailId" value="<?php echo $email; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="MobNo" class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" id="MobNo" name="MobNo" value="<?php echo $mobno; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="Password" name="Password" value="<?php echo $pswd; ?>" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary w-100">Update</button>
                            </form>
                        </div>
                        <div class="card-footer text-muted text-center">
                            <a href="index.php" class="btn btn-link">← Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center py-3 mt-5 bg-light">
        &copy; 2025 Library Management System. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
