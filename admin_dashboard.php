<?php
include 'db_connection.php';
ini_set('session.gc_maxlifetime', 3600); 
session_set_cookie_params(3600);
session_start();

// Encryption/Decryption Key
define('ENCRYPTION_KEY', 'your-encryption-key-here'); 

function encryptData($data) {
    $cipher = "AES-128-CTR";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted_data = openssl_encrypt($data, $cipher, ENCRYPTION_KEY, 0, $iv);
    // Store both encrypted data and IV, separated by ::
    return base64_encode($encrypted_data . "::" . $iv);
}

/**
 * Calculate total amount tracked (sum of all decrypted transaction amounts)
 * @param mysqli $conn
 * @return float
 */
function getTotalAmountTracked($conn) {
    $total = 0;
    $result = $conn->query("SELECT amount FROM transactions");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $amt = $row['amount']; // No decryption!
            if (is_numeric($amt)) {
                $total += $amt;
            }
        }
    }
    return $total;
}

// Decryption function
function decryptData($data) {
    $cipher = "AES-128-CTR";
    $decodedData = base64_decode($data);
    if (!$decodedData || strpos($decodedData, "::") === false) {
        return "Invalid Data";
    }
    list($encrypted_data, $iv) = explode("::", $decodedData, 2);
    return openssl_decrypt($encrypted_data, $cipher, ENCRYPTION_KEY, 0, $iv);
}

// Hash function for transaction amount (for admin display)
function hashTransactionAmount($amount) {
    // Use a strong hash (SHA-256) and a salt (can be ENCRYPTION_KEY)
    return hash_hmac('sha256', $amount, ENCRYPTION_KEY);
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all users
$usersResult = $conn->query("SELECT * FROM users");

// Fetch all transactions
$transactionsResult = $conn->query("SELECT t.*, u.username FROM transactions t JOIN users u ON t.user_id = u.user_id");

if (!$transactionsResult) {
    die("Error fetching transactions: " . $conn->error);
}

// Fetch all help requests
$helpRequestsResult = $conn->query("SELECT * FROM help_requests ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card {
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: #fff;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .sidebar h3 {
            color: #fff;
        }
        .sidebar .nav-item {
            margin-bottom: 10px;
        }
        .sidebar .nav-item a {
            padding: 10px 15px;
            border-radius: 5px;
        }
        .sidebar .nav-item a:hover {
            background-color: #495057;
        }
        .sidebar .nav-item a.active {
            background-color: #007bff;
        }
        .sidebar .nav-item a i {
            margin-right: 10px;
        }
        .sidebar .nav-item a span {
            font-size: 16px;
        }
        .sidebar .nav-item a:hover span {
            font-weight: bold;
        }
        .sidebar .nav-item a.active span {
            font-weight: bold;
        }
        .sidebar .nav-item a i {
            font-size: 20px;
        }

        </style>
   
</head>
<body>
    
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <nav class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
            <h3 class="text-center">Admin Panel</h3>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#transactionsModal">
                        <i class="fas fa-exchange-alt"></i> Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#usersModal">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="fas fa-question-circle"></i> Help Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content: Only show dashboard cards here -->
        <div class="container-fluid p-4">
            <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-primary mb-3 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-users"></i> Total Users</h5>
                    <p class="card-text fs-4"><?php echo $usersResult->num_rows; ?></p>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-success mb-3 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-exchange-alt"></i> Total Transactions</h5>
                    <p class="card-text fs-4"><?php echo $transactionsResult->num_rows; ?></p>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-dark bg-warning mb-3 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-question-circle"></i> Total Help Requests</h5>
                    <p class="card-text fs-4"><?php echo $helpRequestsResult->num_rows; ?></p>
                </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-info mb-3 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-coins"></i> Total Amount Tracked</h5>
                    <p class="card-text fs-4">
                    ₱<?php 
                        $totalAmount = getTotalAmountTracked($conn);
                        echo is_numeric($totalAmount) ? number_format($totalAmount, 2) : "0.00"; 
                    ?>
                    </p>
                </div>
                </div>
            </div>
            </div>
            <!-- Pie Chart Row -->
            <div class="row mt-4">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="card">
                <div class="card-body">
                    <h6 class="mb-3 text-center">Transactions Pie Chart</h6>
                    <div style="width:100%;max-width:400px;margin:auto;">
                    <canvas id="transactionsPieChartMain" style="width:100%;height:auto;max-height:300px;"></canvas>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Query counts for pie chart
            <?php
            $incomeCountMain = $conn->query("SELECT COUNT(*) as cnt FROM transactions WHERE type='income'")->fetch_assoc()['cnt'];
            $expenseCountMain = $conn->query("SELECT COUNT(*) as cnt FROM transactions WHERE type='expense'")->fetch_assoc()['cnt'];
            ?>
            var ctxMain = document.getElementById('transactionsPieChartMain').getContext('2d');
            window.transactionsPieChartMainInstance = new Chart(ctxMain, {
            type: 'pie',
            data: {
                labels: ['Income', 'Expense'],
                datasets: [{
                data: [<?php echo $incomeCountMain; ?>, <?php echo $expenseCountMain; ?>],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)', 
                    'rgba(220, 53, 69, 0.7)'   
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                legend: {
                    position: 'bottom'
                }
                }
            }
            });
        });
        </script>
        <style>
        @media (max-width: 767.98px) {
            .sidebar {
            width: 100% !important;
            min-height: auto !important;
            position: static !important;
            }
            .container-fluid.p-4 {
            padding: 1rem !important;
            }
            .card .card-body {
            padding: 1rem !important;
            }
        }
        </style>
        

        <!-- Transactions Modal -->
        <div class="modal fade" id="transactionsModal" tabindex="-1" aria-labelledby="transactionsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="transactionsModalLabel">All Transactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Re-query transactions for modal
                        $transactionsResultModal = $conn->query("SELECT t.*, u.username FROM transactions t JOIN users u ON t.user_id = u.user_id");
                        while ($transaction = $transactionsResultModal->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $transaction['transaction_id']; ?></td>
                            <td><?php echo $transaction['username']; ?></td>
                            <td><?php echo $transaction['type']; ?></td>
                            <td><?php echo decryptData($transaction['description']); ?></td>
                            <td>
                                ₱<?php 
                                $decryptedAmount = decryptData($transaction['amount']);
                                echo is_numeric($decryptedAmount) ? number_format((float)$decryptedAmount, 2) : "0.00"; 
                                ?>
                            </td>
                            <td>
                                <form action="admin_delete_transaction.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                    <input type="hidden" name="transaction_id" value="<?php echo $transaction['transaction_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Users Modal -->
        <div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="usersModalLabel">All Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Re-query users for modal
                        $usersResultModal = $conn->query("SELECT * FROM users");
                        while ($user = $usersResultModal->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <form action="admin_delete_user.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Help Requests Modal -->
        <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="helpModalLabel">Help Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Re-query help requests for modal
                        $helpRequestsResultModal = $conn->query("SELECT * FROM help_requests ORDER BY created_at DESC");
                        if ($helpRequestsResultModal && $helpRequestsResultModal->num_rows > 0):
                            while ($help = $helpRequestsResultModal->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $help['id']; ?></td>
                                    <td><?php echo htmlspecialchars($help['name']); ?></td>
                                    <td><?php echo htmlspecialchars($help['email']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($help['message'])); ?></td>
                                    <td><?php echo $help['created_at']; ?></td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No help requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Analytics Modal -->
        <div class="modal fade" id="analyticsModal" tabindex="-1" aria-labelledby="analyticsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-info text-dark">
                <h5 class="modal-title" id="analyticsModalLabel">Analytics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row text-center mb-4">
                  <div class="col-4">
                    <div class="card bg-primary text-white">
                      <div class="card-body">
                        <h6>Total Users</h6>
                        <h3><?php echo $usersResult->num_rows; ?></h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="card bg-success text-white">
                      <div class="card-body">
                        <h6>Total Transactions</h6>
                        <h3><?php echo $transactionsResult->num_rows; ?></h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="card bg-warning text-dark">
                      <div class="card-body">
                        <h6>Total Help Requests</h6>
                        <h3><?php echo $helpRequestsResult->num_rows; ?></h3>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                // Transaction breakdown by type
                $incomeCount = $conn->query("SELECT COUNT(*) as cnt FROM transactions WHERE type='income'")->fetch_assoc()['cnt'];
                $expenseCount = $conn->query("SELECT COUNT(*) as cnt FROM transactions WHERE type='expense'")->fetch_assoc()['cnt'];
                $totalAmount = 0;
                $amountResult = $conn->query("SELECT amount FROM transactions");
                while ($row = $amountResult->fetch_assoc()) {
                    $amt = decryptData($row['amount']);
                    if (is_numeric($amt)) $totalAmount += $amt;
                }
                ?>
                <div class="row">
                  <div class="col-6">
                    <div class="card mb-3">
                      <div class="card-body">
                        <h6 class="mb-2">Transaction Breakdown</h6>
                        <p>Income: <strong><?php echo $incomeCount; ?></strong></p>
                        <p>Expense: <strong><?php echo $expenseCount; ?></strong></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card mb-3">
                      <div class="card-body">
                        <h6 class="mb-2">Total Amount Tracked</h6>
                        <p>
                            <strong>
                                ₱<?php 
                                    $totalAmount = getTotalAmountTracked($conn);
                                    echo is_numeric($totalAmount) ? number_format($totalAmount, 2) : "0.00"; 
                                ?>
                            </strong>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <h6 class="mb-3">Transactions Pie Chart</h6>
                        <canvas id="transactionsPieChart" width="400" height="200"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Delete User Function
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `user_id=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'User deleted successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to delete user.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the user.');
                });
            }
        }
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var analyticsModal = document.getElementById('analyticsModal');
    analyticsModal.addEventListener('shown.bs.modal', function () {
        // Prevent multiple charts if modal is opened multiple times
        if (window.transactionsPieChartInstance) {
            window.transactionsPieChartInstance.destroy();
        }
        var ctx = document.getElementById('transactionsPieChart').getContext('2d');
        window.transactionsPieChartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Income', 'Expense'],
                datasets: [{
                    data: [<?php echo $incomeCount; ?>, <?php echo $expenseCount; ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)', 
                        'rgba(220, 53, 69, 0.7)'   
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
});
</script>
</body>
</html>