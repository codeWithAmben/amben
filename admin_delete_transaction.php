<?php
include 'db_connection.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'] ?? null;

    // Validate input
    if (empty($transaction_id) || !is_numeric($transaction_id)) {
        header("Location: admin_dashboard.php?error=Invalid transaction ID.");
        exit();
    }

    // Delete the transaction
    $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?success=Transaction deleted successfully.");
    } else {
        header("Location: admin_dashboard.php?error=Error deleting transaction.");
    }
} else {
    header("Location: admin_dashboard.php?error=Invalid request method.");
}
?>