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
    $type = $_POST['type'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($type) || empty($description) || !is_numeric($amount)) {
        die("Invalid input. Please fill out all fields correctly.");
    }

    // Insert the transaction
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, description, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $user_id, $type, $description, $amount);
    if ($stmt->execute()) {
        header("Location: tracker_dashboard.php?success=Transaction added successfully");
    } else {
        die("Error adding transaction: " . $conn->error);
    }
} else {
    die("Invalid request method.");
}
?>