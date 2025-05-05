<?php
include 'db_connection.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($transaction_id) || !is_numeric($transaction_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid transaction ID.']);
        exit();
    }

    // Fetch the transaction
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $transaction_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        echo json_encode(['success' => true, 'transaction' => $transaction]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaction not found.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Transaction ID not provided.']);
}
?>

