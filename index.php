<?php
session_start();
require('dbconn.php');

if (isset($_POST['signin'])) {
  $u = $_POST['RollNo'];
  $p = $_POST['Password'];

  $sql = "SELECT * FROM LMS.user WHERE RollNo='$u'";
  $result = $conn->query($sql);

  if ($result && $row = $result->fetch_assoc()) {
    $x = $row['Password'];
    $y = $row['Type'];
    if (strcasecmp($x, $p) == 0 && !empty($u) && !empty($p)) {
      $_SESSION['RollNo'] = $u;
      if ($y == 'Admin') {
        header('Location: admin/index.php');
        exit();
      } else {
        header('Location: student/index.php');
        exit();
      }
    }
  }
  $loginError = "Failed to Login! Incorrect RollNo or Password";
}

if (isset($_POST['signup'])) {
  $name = $_POST['Name'];
  $email = $_POST['Email'];
  $password = $_POST['Password'];
  $mobno = $_POST['PhoneNumber'];
  $rollno = $_POST['RollNo'];
  $category = $_POST['Category'];
  $type = 'Student';

  $sql = "INSERT INTO LMS.user (Name, Type, Category, RollNo, EmailId, MobNo, Password)
          VALUES ('$name', '$type', '$category', '$rollno', '$email', '$mobno', '$password')";

  if ($conn->query($sql) === TRUE) {
    $signupSuccess = "Registration Successful";
  } else {
    $signupError = "User already exists or an error occurred";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Library Member Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background: url('images/background.jpg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      z-index: 0;
    }

    .container {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      padding: 30px;
      max-width: 400px;
      width: 100%;
      z-index: 1;
      position: relative;
    }

    .tabs {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
    }

    .tabs button {
      flex: 1;
      padding: 10px;
      background: none;
      border: none;
      color: #fff;
      font-weight: bold;
      border-bottom: 2px solid transparent;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .tabs button.active {
      color: #2196F3;
      border-color: #2196F3;
    }

    form {
      display: none;
      flex-direction: column;
    }

    form.active {
      display: flex;
    }

    input, select {
      margin: 10px 0;
      padding: 12px;
      border: none;
      border-radius: 5px;
      background: #fff;
      color: #000;
    }

    input:focus, select:focus {
      outline: 2px solid #2196F3;
    }

    input[type="submit"] {
      background-color: #2196F3;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #1976D2;
    }

    p {
      margin-top: 10px;
      font-size: 0.9em;
      color: #ccc;
      text-align: center;
    }

    p a {
      color: #2196F3;
      text-decoration: none;
    }

    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 0.8em;
      color: #aaa;
    }

    .alert {
      background: #f44336;
      color: white;
      padding: 10px;
      margin: 10px 0;
      text-align: center;
      border-radius: 5px;
    }

    .success {
      background: #4CAF50;
    }

    @media (max-width: 480px) {
      .container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="overlay"></div>

  <div class="container">
    <div class="tabs">
      <button id="loginTab" class="active" onclick="toggleForm('login')">Login</button>
      <button id="signupTab" onclick="toggleForm('signup')">Sign Up</button>
    </div>

    <!-- Error/Success Messages -->
    <?php if (isset($loginError)) echo "<div class='alert'>$loginError</div>"; ?>
    <?php if (isset($signupSuccess)) echo "<div class='alert success'>$signupSuccess</div>"; ?>
    <?php if (isset($signupError)) echo "<div class='alert'>$signupError</div>"; ?>

    <!-- Login Form -->
    <form id="loginForm" class="active" method="post" action="index.php">
      <input type="text" name="RollNo" placeholder="Roll Number" required />
      <input type="password" name="Password" placeholder="Password" required />
      <input type="submit" name="signin" value="Sign In" />
    </form>

    <!-- Sign Up Form -->
    <form id="signupForm" method="post" action="index.php">
      <input type="text" name="Name" placeholder="Full Name" required />
      <input type="text" name="Email" placeholder="Email Address" required />
      <input type="password" name="Password" placeholder="Password" required />
      <input type="text" name="PhoneNumber" placeholder="Phone Number" required />
      <input type="text" name="RollNo" placeholder="Roll Number" required />
      <select name="Category" required>
        <option value="">Select Category</option>
        <option value="GEN">General</option>
        <option value="OBC">OBC</option>
        <option value="SC">SC</option>
        <option value="ST">ST</option>
      </select>
      <input type="submit" name="signup" value="Sign Up" />
      <p>By creating an account, you agree to our <a href="terms.html">Terms</a></p>
    </form>

    <div class="footer">
      &copy; 2025 Library Member Login. All Rights Reserved<br>
      <a href="about.html">About the Project</a>
    </div>
  </div>

  <script>
    function toggleForm(type) {
      document.getElementById('loginForm').classList.remove('active');
      document.getElementById('signupForm').classList.remove('active');
      document.getElementById('loginTab').classList.remove('active');
      document.getElementById('signupTab').classList.remove('active');

      if (type === 'login') {
        document.getElementById('loginForm').classList.add('active');
        document.getElementById('loginTab').classList.add('active');
      } else {
        document.getElementById('signupForm').classList.add('active');
        document.getElementById('signupTab').classList.add('active');
      }
    }
  </script>
</body>
</html>
