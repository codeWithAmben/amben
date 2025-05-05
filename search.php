<?php
include 'db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$userQuery = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userInfo = $userResult->fetch_assoc();

// Fetch transactions for the logged-in user
$user_id = $_SESSION['user_id'];

// Handle search query
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? AND (description LIKE ? OR type LIKE ? OR amount LIKE ?)");
    $searchTerm = "%$search%";
    $stmt->bind_param("isss", $user_id, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
}
// Fetch totals for Expense and Income based on search query
if (!empty($search)) {
    $expenseStmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_expense FROM transactions WHERE user_id = ? AND type = 'Expense' AND (description LIKE ? OR type LIKE ? OR amount LIKE ?)");
    $incomeStmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_income FROM transactions WHERE user_id = ? AND type = 'Income' AND (description LIKE ? OR type LIKE ? OR amount LIKE ?)");
    $expenseStmt->bind_param("isss", $user_id, $searchTerm, $searchTerm, $searchTerm);
    $incomeStmt->bind_param("isss", $user_id, $searchTerm, $searchTerm, $searchTerm);
} else {
    $expenseStmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_expense FROM transactions WHERE user_id = ? AND type = 'Expense'");
    $incomeStmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_income FROM transactions WHERE user_id = ? AND type = 'Income'");
    $expenseStmt->bind_param("i", $user_id);
    $incomeStmt->bind_param("i", $user_id);
}

$expenseStmt->execute();
$expenseResult = $expenseStmt->get_result();
$totalExpense = $expenseResult->fetch_assoc()['total_expense'];

$incomeStmt->execute();
$incomeResult = $incomeStmt->get_result();
$totalIncome = $incomeResult->fetch_assoc()['total_income'];

$total = $totalIncome - $totalExpense;

$stmt->execute();
$result = $stmt->get_result();

// Filter results based on the search query if provided
$filteredResults = [];

if (!empty($search)) {
    while ($row = $result->fetch_assoc()) {
        if (stripos($row['description'], $search) !== false || 
            stripos($row['type'], $search) !== false || 
            stripos((string)$row['amount'], $search) !== false) {
            $filteredResults[] = $row;
        }
    }

    // Debugging: Check filtered results
    if (!empty($filteredResults)) {
        echo "<script>console.log('Filtered results: " . json_encode($filteredResults) . "');</script>";
    } else {
        echo "<script>console.log('No results found for the search query.');</script>";
    }
}

$filteredResultObject = new ArrayObject($filteredResults);
    

$stmt->close();
$expenseStmt->close();
$incomeStmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search Transactions</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            }
            .logo_head, .logo {
            font-size: 28px;
            font-weight: 600;
            color: #f5f5f5;
            text-align: center;
            margin-bottom: 20px;
            }
            .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to right, rgb(2, 110, 85), rgb(76, 175, 145));
            margin-top: 50px;
            }
            .form-container input, .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            }
            .form-container input {
            border: 1px solid #ccc;
            }
            .form-container button {
            background-color: rgb(27, 150, 58);
            color: white;
            border: none;
            cursor: pointer;
            }
            .form-container button:hover {
            background-color: rgb(33, 181, 161);
            }
            .table-container {
            margin: 20px auto;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .table-container table {
            width: 100%;
            border-collapse: collapse;
            }
            .table-container th, .table-container td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            }
            .table-container th {
            background-color: rgb(2, 110, 85);
            color: #fff;
            }
            .table-container tr:hover {
            background-color: #f1f1f1;
            }
            .table-container .total-row {
            font-weight: bold;
            background-color: rgb(2, 110, 85);
            color: #fff;
            }
            .table-container .no-results {
            text-align: center;
            padding: 20px;
            color: #999;
            }
            .header {
            background: linear-gradient(to right, rgb(2, 110, 85), rgb(76, 175, 145));
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            }
            .header nav a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
        font-size: 0.918rem;
        font-family: 'Poppins', sans-serif;
        position: relative;
    }

    .header nav a .hover-line {
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: white;
        transition: width 0.3s ease-in-out;
    }

    .header nav a:hover .hover-line {
        width: 100%;
    }
        </style>
    </head>
    <body>
        
        <div class="header d-flex justify-content-between align-items-center">
            <nav class="d-flex align-items-center">
            <a href="home.php" class="text-white text-decoration-none mx-2" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
            Home
            <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
            </a>
            <a href="about.php" class="text-white text-decoration-none mx-2" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
            About
            <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
            </a>
        </nav>
        <div class="logo_head text-center flex-grow-1" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">TrueTally</div>
            
            <nav class="d-flex align-items-center">
            <a href="tracker_dashboard.php" class="text-white text-decoration-none mx-2" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
            Tracker Dashboard
            <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
            </a>
            </nav>
        </div>
        </div>
            </form>
        </div>

        <div class="container mt-5">
            <div class="form-container">
                <h2 class="logo">Search Transactions</h2>
                <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" name="search" placeholder="Search by description, type, or amount" value="<?php echo htmlspecialchars($search); ?>" required>
                    <button type="submit">Search</button>
                </form>
            </div>

            <?php if ($filteredResultObject->count() > 0): ?>
                <div class="table-container mt-4">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Amount</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredResultObject as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                   
                                </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="2">Total Income</td>
                                <td colspan="2"><?php echo htmlspecialchars($totalIncome); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="2">Total Expense</td>
                                <td colspan="2"><?php echo htmlspecialchars($totalExpense); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="2">Net Total</td>
                                <td colspan="2"><?php echo htmlspecialchars($total); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="table-container mt-4">
                    <p class="no-results">No results found for the search query.</p>
                </div>
            <?php endif; ?>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</html>
