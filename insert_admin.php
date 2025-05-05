<?php
include 'db_connection.php';

// Admin credentials
$username = "admin"; 
$password = "admin123";

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the admin account into the database
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>