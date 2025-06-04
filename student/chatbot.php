<?php
session_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    header("Location: ../index.php");
    exit;
}

$roll = $_SESSION['RollNo'];
$response = "";

function handleMessage($conn, $roll, $message) {
    $msg = strtolower(trim($message));

    if (strpos($msg, 'issued') !== false) {
        return showIssuedBooks($conn, $roll);
    } elseif (strpos($msg, 'suggest') !== false) {
        return suggestBook($conn);
    } elseif (strpos($msg, 'dues') !== false || strpos($msg, 'due') !== false) {
        return checkDues($conn, $roll);
    } elseif (strpos($msg, 'renewals') !== false || strpos($msg, 'renew') !== false) {
        return showRenewals($conn, $roll);
    } elseif (strpos($msg, 'help') !== false || strpos($msg, 'options') !== false) {
        return getHelp();
    } else {
        return "🤔 I'm not sure what you mean. Try typing 'help' to see what I can do.";
    }
}

function showIssuedBooks($conn, $roll) {
    $stmt = $conn->prepare("SELECT b.Title, r.BookId, r.Due_Date FROM LMS.record r JOIN LMS.book b ON r.BookId = b.BookId WHERE r.RollNo = ? AND r.Date_of_Return IS NULL");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) return "📚 You have no issued books.";

    $output = "<strong>Your Issued Books:</strong><br><ul>";
    while ($row = $res->fetch_assoc()) {
        $output .= "<li><strong>" . htmlspecialchars($row['Title']) . "</strong> (ID: " . $row['BookId'] . ") - Due: " . $row['Due_Date'] . "</li>";
    }
    $output .= "</ul>";

    return $output;
}

function suggestBook($conn) {
    $sql = "SELECT Title FROM LMS.book WHERE Availability > 0 ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        return "📖 How about reading: <strong>" . htmlspecialchars($row['Title']) . "</strong>?";
    }
    return "😢 No books are currently available to suggest.";
}

function checkDues($conn, $roll) {
    $stmt = $conn->prepare("SELECT SUM(Dues) AS TotalDues FROM LMS.record WHERE RollNo = ? AND Dues > 0");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $total = $result['TotalDues'] ?? 0;

    if ($total > 0) {
        return "💰 You have <strong>₹$total</strong> in pending dues.";
    }
    return "✅ You have no dues.";
}

function showRenewals($conn, $roll) {
    $stmt = $conn->prepare("SELECT b.Title, r.Renewals_left FROM LMS.record r JOIN LMS.book b ON r.BookId = b.BookId WHERE r.RollNo = ? AND r.Date_of_Return IS NULL");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) return "🔁 No books to renew.";

    $output = "<strong>Renewal Information:</strong><br><ul>";
    while ($row = $res->fetch_assoc()) {
        $output .= "<li><strong>" . htmlspecialchars($row['Title']) . "</strong> - Renewals left: " . $row['Renewals_left'] . "</li>";
    }
    $output .= "</ul>";

    return $output;
}

function getHelp() {
    return "🤖 I can help with the following:<br>"
         . "- 'Show issued books'<br>"
         . "- 'Suggest me a book'<br>"
         . "- 'Check dues'<br>"
         . "- 'Show renewals left'<br>"
         . "- 'Help' to see this message again.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $response = handleMessage($conn, $roll, $_POST['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Chatbot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .chat-container { max-width: 600px; margin: 60px auto; }
    .chat-box { height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 1rem; background: #f9f9f9; }
    .user-msg { text-align: right; color: #0d6efd; }
    .bot-msg { text-align: left; color: #198754; }
  </style>
</head>
<body class="bg-light">
  <div class="container chat-container">
    <div class="card">
      <div class="card-header bg-dark text-white">🤖 LMS Chatbot Assistant</div>
      <div class="card-body">
        <div class="chat-box mb-3">
          <?php if ($response): ?>
            <div class="user-msg"><strong>You:</strong> <?= htmlspecialchars($_POST['message']) ?></div>
            <div class="bot-msg mt-2"><strong>Bot:</strong> <?= $response ?></div>
          <?php else: ?>
            <div class="bot-msg"><strong>Bot:</strong> Hello! Type 'help' to see what I can do.</div>
          <?php endif; ?>
        </div>
        <form method="post">
          <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
            <button class="btn btn-primary" type="submit">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
