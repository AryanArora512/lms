<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $bookid = intval($_GET['id']);
    $roll = $_SESSION['RollNo'];

    // Check if already issued
    $check = $conn->prepare("SELECT 1 FROM LMS.record WHERE RollNo = ? AND BookId = ? AND Date_of_Return IS NULL");
    $check->bind_param("si", $roll, $bookid);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('You have already issued this book.'); window.location.href='book.php';</script>";
    } else {
        // Insert request
        $stmt = $conn->prepare("INSERT INTO LMS.requests (RollNo, BookId) VALUES (?, ?)");
        $stmt->bind_param("si", $roll, $bookid);

        if ($stmt->execute()) {
            echo "<script>alert('Book request submitted successfully!'); window.location.href='book.php';</script>";
        } else {
            echo "<script>alert('Failed to request book. Try again.'); window.location.href='book.php';</script>";
        }

        $stmt->close();
    }

    $check->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='book.php';</script>";
}
?>
