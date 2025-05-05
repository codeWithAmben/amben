<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if transaction_id is provided
if (!isset($_GET['transaction_id'])) {
    die("Transaction ID is required.");
}

$transaction_id = $_GET['transaction_id'];

// Fetch the transaction details
$stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ? AND user_id = ?");
$stmt->bind_param("ii", $transaction_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Transaction not found or you do not have permission to edit it.");
}

$transaction = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php if (isset($_SESSION['notification'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['notification']; unset($_SESSION['notification']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h2>Edit Transaction</h2>
        <div class="alert alert-warning" role="alert" id="edit-warning">
            Are you sure you want to edit this transaction?
        </div>
        <form action="update_transaction.php" method="POST" onsubmit="return confirmEdit();">
            <input type="hidden" name="transaction_id" value="<?php echo $transaction['transaction_id']; ?>">
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="Income" <?php echo $transaction['type'] === 'Income' ? 'selected' : ''; ?>>Income</option>
                    <option value="Expense" <?php echo $transaction['type'] === 'Expense' ? 'selected' : ''; ?>>Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" id="description" class="form-control" value="<?php echo $transaction['description']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" value="<?php echo $transaction['amount']; ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="tracker_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script>
        function confirmEdit() {
            return confirm("Are you sure you want to save changes to this transaction?");
        }
    </script>
</body>
</html>