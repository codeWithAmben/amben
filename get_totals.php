<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch totals for Expense and Income
$expenseResult = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total_expense FROM transactions WHERE user_id = $user_id AND type = 'Expense'");
$incomeResult = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total_income FROM transactions WHERE user_id = $user_id AND type = 'Income'");

$totalExpense = $expenseResult->fetch_assoc()['total_expense'];
$totalIncome = $incomeResult->fetch_assoc()['total_income'];

echo json_encode([
    'success' => true,
    'totalExpense' => $totalExpense,
    'totalIncome' => $totalIncome
]);
?>