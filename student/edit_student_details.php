<?php
ob_start();
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Student Details - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="css/custom-style.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Library System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle"></i> <span class="d-none d-sm-inline">Account</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="index.php"><i class="fas fa-id-badge me-2"></i> Your Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4">
  <h3 class="mb-4 text-center">✏️ Update Your Details</h3>

  <?php
  $rollno = $_SESSION['RollNo'];
  $sql = "SELECT * FROM LMS.user WHERE RollNo='$rollno'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();

  $name = $row['Name'];
  $category = $row['Category'];
  $email = $row['EmailId'];
  $mobno = $row['MobNo'];
  $pswd = $row['Password'];
  ?>

  <form method="post" action="edit_student_details.php?id=<?php echo $rollno; ?>" class="row g-4">
    <div class="col-md-6">
      <label for="Name" class="form-label">Name</label>
      <input type="text" class="form-control" id="Name" name="Name" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>

    <div class="col-md-6">
      <label for="Category" class="form-label">Category</label>
      <select id="Category" name="Category" class="form-select" required>
        <option selected value="<?php echo $category; ?>"><?php echo $category; ?></option>
        <option value="GEN">GEN</option>
        <option value="OBC">OBC</option>
        <option value="SC">SC</option>
        <option value="ST">ST</option>
      </select>
    </div>

    <div class="col-md-6">
      <label for="EmailId" class="form-label">Email ID</label>
      <input type="email" class="form-control" id="EmailId" name="EmailId" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>

    <div class="col-md-6">
      <label for="MobNo" class="form-label">Mobile Number</label>
      <input type="text" class="form-control" id="MobNo" name="MobNo" value="<?php echo htmlspecialchars($mobno); ?>" required>
    </div>

    <div class="col-md-6">
      <label for="Password" class="form-label">Password</label>
      <input type="password" class="form-control" id="Password" name="Password" value="<?php echo htmlspecialchars($pswd); ?>" required>
    </div>

    <div class="col-12">
      <button type="submit" name="submit" class="btn btn-primary">Update Details</button>
    </div>
  </form>

  <?php
  if (isset($_POST['submit'])) {
      $rollno = $_GET['id'];
      $name = $_POST['Name'];
      $category = $_POST['Category'];
      $email = $_POST['EmailId'];
      $mobno = $_POST['MobNo'];
      $pswd = $_POST['Password'];

      $sql1 = "UPDATE LMS.user SET 
                Name='$name', 
                Category='$category', 
                EmailId='$email', 
                MobNo='$mobno', 
                Password='$pswd' 
                WHERE RollNo='$rollno'";

      if ($conn->query($sql1) === TRUE) {
          echo "<script>alert('Details updated successfully.'); window.location.href='index.php';</script>";
      } else {
          echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
      }
  }
  ?>
</div>


<footer class="footer mt-auto py-3 bg-light text-center">
  <div class="container">
    <small class="text-muted">&copy; 2025 Library Management System. All rights reserved.</small>
  </div>
</footer>


</body>
</html>
