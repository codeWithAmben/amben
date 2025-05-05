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
    $type = $_POST['type'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    // Validate input
    if (empty($transaction_id) || empty($type) || empty($description) || !is_numeric($amount)) {
        die("Invalid input. Please fill out all fields correctly.");
    }

    // Update the transaction
    $stmt = $conn->prepare("UPDATE transactions SET type = ?, description = ?, amount = ? WHERE transaction_id = ? AND user_id = ?");
    $stmt->bind_param("ssdii", $type, $description, $amount, $transaction_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        header("Location: tracker_dashboard.php?success=Transaction updated successfully");
    } else {
        die("Error updating transaction: " . $conn->error);
    }
} else {
    die("Invalid request method.");
}
?>