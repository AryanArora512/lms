<?php
session_start();
require('dbconn.php');

// Redirect if not logged in or ID is missing
if (!isset($_SESSION['RollNo']) || !isset($_GET['id'])) {
    header("Location: book.php");
    exit;
}

$roll = $_SESSION['RollNo'];
$bookid = intval($_GET['id']);

// Delete the request from the database
$stmt = $conn->prepare("DELETE FROM LMS.requests WHERE RollNo = ? AND BookId = ?");
$stmt->bind_param("si", $roll, $bookid);
$stmt->execute();

// Redirect with alert
echo "<script>
    alert('Request cancelled.');
    window.location.href = 'book.php';
</script>";
?>
