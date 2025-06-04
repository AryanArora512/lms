<?php
session_start();


require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    echo "<script>alert('Access Denied!'); window.location.href='login.php';</script>";
    exit;
}

$adminRollNo = $_SESSION['RollNo'];
$messageStatus = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $receiverRollNo = trim($_POST['RollNo']);
    $message = trim($_POST['Message']);

    if (!empty($receiverRollNo) && !empty($message)) {
        $receiverRollNo = mysqli_real_escape_string($conn, $receiverRollNo);
        $message = mysqli_real_escape_string($conn, $message);

        $checkSQL = "SELECT RollNo FROM LMS.user WHERE RollNo = '$receiverRollNo' LIMIT 1";
        $receiverExists = $conn->query($checkSQL);

        if ($receiverExists && $receiverExists->num_rows > 0) {
            $insertSQL = "INSERT INTO LMS.message (RollNo, Msg, Date, Time) 
                          VALUES ('$receiverRollNo', '$message', CURDATE(), CURTIME())";

            if ($conn->query($insertSQL) === TRUE) {
                $messageStatus = "<div class='alert alert-success'>✅ Message sent to <strong>$receiverRollNo</strong>.</div>";
            } else {
                $messageStatus = "<div class='alert alert-danger'>❌ DB Error: " . $conn->error . "</div>";
            }
        } else {
            $messageStatus = "<div class='alert alert-warning'>⚠️ Roll No <strong>$receiverRollNo</strong> not found.</div>";
        }
    } else {
        $messageStatus = "<div class='alert alert-warning'>⚠️ Both fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send Message - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom-style.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>


<div class="d-flex">
  <?php include 'sidebar.php'; ?>

  <!-- Main content with padding-top to avoid overlapping the fixed navbar -->
  <div class="content-wrapper flex-grow-1" style="padding: 80px 20px 20px 20px;">
    <div class="container-fluid">
      <?= $messageStatus ?>

      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">📨 Send a Message</h5>
        </div>
        <div class="card-body">
          <form method="post" action="message.php" class="needs-validation" novalidate>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="RollNo" class="form-label">Receiver Roll No</label>
                <input type="text" class="form-control" id="RollNo" name="RollNo" placeholder="e.g., ST123456" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="Message" class="form-label">Message</label>
              <textarea class="form-control" id="Message" name="Message" rows="4" maxlength="255" placeholder="Enter your message here..." required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<footer class="footer">
  &copy; 2025 Library Management System. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>

</body>
</html>
