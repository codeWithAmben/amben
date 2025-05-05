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
    $user_id = $_POST['user_id'] ?? null;

    // Validate input
    if (empty($user_id) || !is_numeric($user_id)) {
        header("Location: admin_dashboard.php?error=Invalid user ID.");
        exit();
    }

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?success=User deleted successfully.");
    } else {
        header("Location: admin_dashboard.php?error=Error deleting user.");
    }
} else {
    header("Location: admin_dashboard.php?error=Invalid request method.");
}
?>