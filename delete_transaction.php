<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'];

    // Validate input
    if (empty($transaction_id) || !is_numeric($transaction_id)) {
        die("Invalid transaction ID.");
    }

    // Delete the transaction
    $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $transaction_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        header("Location: tracker_dashboard.php?success=Transaction deleted successfully");
    } else {
        die("Error deleting transaction: " . $conn->error);
    }
} else {
    die("Invalid request method.");
}
?>
