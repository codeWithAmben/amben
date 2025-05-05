<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
  
    exit();
}

// Fetch user name only
$user_id = $_SESSION['user_id'];
$userQuery = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userInfo = $userResult->fetch_assoc();

// Fetch totals for Expense and Income
$expenseStmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_expense FROM transactions WHERE user_id = ? AND type = 'Expense'");
$expenseStmt->bind_param("i", $user_id);
$expenseStmt->execute();
$expenseResult = $expenseStmt->get_result();
$totalExpense = $expenseResult->fetch_assoc()['total_expense'];

$incomeStmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_income FROM transactions WHERE user_id = ? AND type = 'Income'");
$incomeStmt->bind_param("i", $user_id);
$incomeStmt->execute();
$incomeResult = $incomeStmt->get_result();
$totalIncome = $incomeResult->fetch_assoc()['total_income'];
$total = $totalIncome - $totalExpense;

$stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY amount ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueTally</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="floating-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: radial-gradient(circle, rgba(76,175,80,0.2) 0%, rgba(255,255,255,0) 70%);"></div>
    <div class="floating-element position-absolute" style="top: 10%; left: 5%; width: 100px; height: 100px; background: rgba(76, 175, 80, 0.3); border-radius: 50%; animation: float1 5s infinite;"></div>
    <div class="floating-element position-absolute" style="top: 20%; right: 15%; width: 120px; height: 120px; background: rgba(255, 193, 7, 0.3); border-radius: 50%; animation: float3 7s infinite;"></div>


    <style>
        @keyframes float1 {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }

        @keyframes float2 {
            0% { transform: translateY(0); }
            50% { transform: translateY(30px); }
            100% { transform: translateY(0); }
        }


        @keyframes float3 {
            0% { transform: translateY(0); }
            50% { transform: translateY(25px); }
            100% { transform: translateY(0); }
        }
        #calculator {
        font-family: 'Poppins', sans-serif;
    }
    .calculator-buttons .btn {
        margin: 2px;
        font-size: 1.2rem;
    }
    #calc-display {
        font-size: 1.5rem;
        height: 50px;
    }
    </style>
    </style>
    <div id="header" class="d-flex justify-content-between align-items-center bg-primary text-white py-3 px-4 flex-wrap">
        <div class="text-center w-100 mb-2 mb-md-0">
            <span id="title" class="h5 mb-0" style="font-family: 'Poppins', sans-serif; font-weight: bold; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); font-size: 102%;">TrueTally</span>
        </div>
        <div class="nav-links d-flex gap-3 flex-wrap justify-content-center">
            <a href="home.php" class="text-white text-decoration-none" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
                Home
                <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
            </a>
            <a href="about.php" class="text-white text-decoration-none" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
                About
                <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
            </a>
            <a href="search.php" class="text-white text-decoration-none" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
                Search
                <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
            </a>
        </div>
        </form>
        <button class="btn logout-button text-white text-decoration-none mt-2 mt-md-0" onclick="confirmLogout()" style="position: relative; font-family: 'Poppins', sans-serif; font-size: 0.918rem;">
            Logout
            <span class="hover-line" style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background-color: white; transition: width 0.3s;"></span>
        </button>
            <script>
            document.querySelectorAll('.nav-links a, .logout-button').forEach(link => {
                link.addEventListener('mouseover', function () {
                    this.querySelector('.hover-line').style.width = '100%';
                });
                link.addEventListener('mouseout', function () {
                    this.querySelector('.hover-line').style.width = '0';
                });
            });
        </script>
        <script>
            function confirmLogout() {
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = 'logout.php';
                }
            }
        </script>
    </div>
            
        </script>
    </div>
    <div id="profile-info" class="bg-light p-3 mb-4 rounded shadow-sm" style="font-family: 'Poppins', sans-serif;">
    <h5 class="mb-0">Welcome, <?php echo htmlspecialchars($userInfo['username']); ?>!</h5>
</div>
    <div id="dashboard">
        <div class="data-panel" id="expense-panel">
            <i class="fas fa-shopping-cart"></i>
            <div>Expense: ₱<?php echo number_format($totalExpense, 2); ?></div>
        </div>

        <div class="data-panel" id="income-panel">
            <i class="fas fa-money-bill-wave"></i>
            <div>Income: ₱<?php echo number_format($totalIncome, 2); ?></div>
        </div>

        <div class="data-panel" id="total-panel">
            <i class="fas fa-chart-pie"></i>
            <div>Total: ₱<?php echo number_format($total, 2); ?></div>
        </div>

        <div id="buttons">
            <button class="btn btn-success" onclick="toggleAddTransactionForm()">
                <i class="fas fa-plus"></i> Add Transaction
            </button>
        </div>
        <button id="toggle-calculator" class="btn btn-primary mt-4">
    <i class="fas fa-calculator"></i> Show Calculator
</button>

        <table id="transaction-table">
            <thead>
            <tr>
               
                <th>Type</th>
                <th>Description</th>
                <th id="amount-header">Amount <i class="fas fa-sort"></i></th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
    <?php while ($transaction = $result->fetch_assoc()): ?>
    <tr>
       
        <td><?php echo $transaction['type']; ?></td>
        <td><?php echo $transaction['description']; ?></td>
        <td>₱<?php echo number_format($transaction['amount'], 2); ?></td>
        <td>
            <a href="edit_transaction.php?transaction_id=<?php echo $transaction['transaction_id']; ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="delete_transaction.php" method="POST" style="display: inline;" onsubmit="return confirmDelete(this);">
                <input type="hidden" name="transaction_id" value="<?php echo $transaction['transaction_id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <script>
                function confirmDelete(form) {
                    if (confirm('Are you sure you want to delete this transaction?')) {
                        // Show delete notification
                        const deleteNotification = document.getElementById('deleteNotification');
                        deleteNotification.style.display = 'block';
                        setTimeout(() => {
                            deleteNotification.style.display = 'none';
                        }, 3000);
                        return true;
                    }
                    return false;
                }
            </script>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>
        </table>

        <div id="addTransactionForm" style="display: none; position: absolute; top: 20%; left: 50%; transform: translate(-50%, 0); z-index: 1000; width: 50%;">
        <div class="card shadow-sm animate__animated animate__fadeIn">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Add Transaction</h5>
        </div>
        <div class="card-body">
            <form action="add_transaction.php" method="POST" onsubmit="return confirm('Are you sure you want to add this transaction?')">
            <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-control" required>
            <option value="Income">Income</option>
            <option value="Expense">Expense</option>
            </select>
            </div>
            <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-success" onclick="showSuccessNotification()">Add Transaction</button>
            <button type="button" class="btn btn-secondary" onclick="toggleAddTransactionForm()">Cancel</button>
            </form>
        </div>
        </div>
        <div id="successNotification" class="alert alert-success mt-3 animate__animated animate__fadeIn" style="display: none;">
            Transaction added successfully!
        </div>
        <div id="editNotification" class="alert alert-success mt-3 animate__animated animate__fadeIn" style="display: none;">
    Transaction updated successfully!
</div>
<div id="deleteNotification" class="alert alert-danger mt-3 animate__animated animate__fadeIn" style="display: none;">
    Transaction deleted successfully!
</div>
        </div>

        <script>
            function showSuccessNotification() {
            const notification = document.getElementById('successNotification');
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
            }
        </script>
        </div>
        </div>
    </div>
    </div>
</div>

    </div>
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaction</h5>
                <button type="button" class="btn-close" onclick="closeEditModal()"></button>
            </div>
            <div class="modal-body">
                <form id="editTransactionForm">
                    <input type="hidden" id="edit-transaction-id">
                    <div class="mb-3">
                        <label for="edit-type" class="form-label">Type</label>
                        <select id="edit-type" class="form-control" required>
                            <option value="Income">Income</option>
                            <option value="Expense">Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <input type="text" id="edit-description" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-amount" class="form-label">Amount</label>
                        <input type="number" id="edit-amount" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</html>

<script>
    <!-- Initialize totals from the database -->
    let totalExpense = <?php echo $totalExpense; ?>;
    let totalIncome = <?php echo $totalIncome; ?>;

    // Function to update the dashboard totals
    function updateDashboardTotals() {
        const total = totalIncome - totalExpense;

        document.getElementById('expense-panel').querySelector('div').textContent = `Expense: ₱${totalExpense.toFixed(2)}`;
        document.getElementById('income-panel').querySelector('div').textContent = `Income: ₱${totalIncome.toFixed(2)}`;
        document.getElementById('total-panel').querySelector('div').textContent = `Total: ₱${total.toFixed(2)}`;
    }

    // Call the function to update the dashboard on page load
    updateDashboardTotals();

    // Show the Add Transaction dialog
    document.getElementById('add-transaction').addEventListener('click', function () {
        document.getElementById('add-transaction-dialog').style.display = 'block';
    });

    // Hide the Add Transaction dialog
    document.getElementById('cancel-transaction-button').addEventListener('click', function () {
        document.getElementById('add-transaction-dialog').style.display = 'none';
    });

    // Add Transaction Button Click Event
    document.getElementById('add-transaction-button').addEventListener('click', function () {
        const type = document.getElementById('transaction-type').value;
        const description = document.getElementById('transaction-description').value;
        const amount = parseFloat(document.getElementById('transaction-amount').value);

        if (!type) {
            alert('Please select a transaction type.');
            return;
        }

        if (description && amount && !isNaN(amount)) {
            fetch('add_transaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `type=${type}&description=${description}&amount=${amount}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh totals
                    fetchAndUpdateTotals();

                    // Add the new transaction to the table
                    const table = document.getElementById('transaction-table').getElementsByTagName('tbody')[0];
                    const newRow = table.insertRow();

                    const idCell = newRow.insertCell(0);
                    const typeCell = newRow.insertCell(1);
                    const descriptionCell = newRow.insertCell(2);
                    const amountCell = newRow.insertCell(3);
                    const actionCell = newRow.insertCell(4);

                    idCell.textContent = data.transactionId;
                    typeCell.innerHTML = `<span class="${type.toLowerCase()}-type">${type}</span>`;
                    descriptionCell.textContent = description;
                    amountCell.textContent = `$${amount.toFixed(2)}`;
                    actionCell.innerHTML = `
                        <button class="delete-button" data-transaction-id="${data.transactionId}" onclick="deleteTransaction(${data.transactionId})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    `;

                    // Reset the form and hide the dialog
                    document.getElementById('add-transaction-dialog').style.display = 'none';
                    document.getElementById('transaction-description').value = '';
                    document.getElementById('transaction-amount').value = '';
                } else {
                    alert('Failed to add transaction.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Success!');
            });
        } else {
            alert('Please enter a valid description and amount.');
        }
    });


    document.getElementById('toggle-navbar').addEventListener('click', function () {
        const navbar = document.getElementById('navbar');

        if (navbar.style.display === 'none' || navbar.style.display === '') {
            navbar.style.display = 'flex'; // Show the navbar
        } else {
            navbar.style.display = 'none'; // Hide the navbar
        }
    });
    document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const strengthIndicator = document.getElementById('password-strength');
    const strengthMessage = document.getElementById('password-strength-message');

    // Define password strength criteria
    const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const mediumPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;

    if (strongPassword.test(password)) {
        strengthIndicator.style.width = '100%';
        strengthIndicator.style.backgroundColor = 'green';
        strengthMessage.textContent = 'Strong password';
    } else if (mediumPassword.test(password)) {
        strengthIndicator.style.width = '60%';
        strengthIndicator.style.backgroundColor = 'orange';
        strengthMessage.textContent = 'Medium password';
    } else {
        strengthIndicator.style.width = '30%';
        strengthIndicator.style.backgroundColor = 'red';
        strengthMessage.textContent = 'Weak password';
    }document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const strengthIndicator = document.getElementById('password-strength');
    const strengthMessage = document.getElementById('password-strength-message');

    // Define password strength criteria
    const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const mediumPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;

    if (strongPassword.test(password)) {
        strengthIndicator.style.width = '100%';
        strengthIndicator.classList.remove('bg-warning', 'bg-danger');
        strengthIndicator.classList.add('bg-success');
        strengthMessage.textContent = 'Strong password';
        strengthMessage.classList.remove('text-danger', 'text-warning');
        strengthMessage.classList.add('text-success');
    } else if (mediumPassword.test(password)) {
        strengthIndicator.style.width = '60%';
        strengthIndicator.classList.remove('bg-success', 'bg-danger');
        strengthIndicator.classList.add('bg-warning');
        strengthMessage.textContent = 'Medium password';
        strengthMessage.classList.remove('text-success', 'text-danger');
        strengthMessage.classList.add('text-warning');
    } else {
        strengthIndicator.style.width = '30%';
        strengthIndicator.classList.remove('bg-success', 'bg-warning');
        strengthIndicator.classList.add('bg-danger');
        strengthMessage.textContent = 'Weak password';
        strengthMessage.classList.remove('text-success', 'text-warning');
        strengthMessage.classList.add('text-danger');
    }
});
});
function editTransaction(transactionId) {
    console.log('Fetching transaction with ID:', transactionId); // Debugging

    fetch('get_transaction.php?transaction_id=' + transactionId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the modal with transaction details
                document.getElementById('edit-transaction-id').value = data.transaction.transaction_id;
                document.getElementById('edit-type').value = data.transaction.type;
                document.getElementById('edit-description').value = data.transaction.description;
                document.getElementById('edit-amount').value = data.transaction.amount;

                // Show the modal
                document.getElementById('editTransactionModal').classList.add('show');
            } else {
                alert(data.message || 'Failed to fetch transaction details.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching transaction details.');
        });
}

// Handle the form submission for editing a transaction
document.getElementById('editTransactionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const transactionId = document.getElementById('edit-transaction-id').value;
    const type = document.getElementById('edit-type').value;
    const description = document.getElementById('edit-description').value;
    const amount = parseFloat(document.getElementById('edit-amount').value);

    if (!type || !description || isNaN(amount)) {
        alert('Please fill out all fields correctly.');
        return;
    }

    // Send the updated transaction details to the server
    fetch('edit_transaction.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `transaction_id=${transactionId}&type=${type}&description=${description}&amount=${amount}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditNotification(); // Show edit notification
                setTimeout(() => location.reload(), 3000); // Reload after notification
            } else {
                alert('Failed to update transaction.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the transaction.');
        });
});

function editTransaction(transactionId) {
    console.log('Fetching transaction with ID:', transactionId); // Debugging

    fetch('get_transaction.php?transaction_id=' + transactionId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the modal with transaction details
                document.getElementById('edit-transaction-id').value = data.transaction.transaction_id;
                document.getElementById('edit-type').value = data.transaction.type;
                document.getElementById('edit-description').value = data.transaction.description;
                document.getElementById('edit-amount').value = data.transaction.amount;

                // Show the modal
                document.getElementById('editTransactionModal').classList.add('show');
            } else {
                alert(data.message || 'Failed to fetch transaction details.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching transaction details.');
        });
        
}

    function toggleAddTransactionForm() {
        const form = document.getElementById('addTransactionForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }


</script>
<div id="calculator-container" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
    <div id="calculator" class="card shadow-sm mt-4" style="max-width: 300px; margin: auto;">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Calculator</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="calc-display" class="form-control text-end" placeholder="0" readonly>
            </div>
            <div class="calculator-buttons">
                <div class="row mb-2">
                    <button class="btn btn-light col" onclick="appendToDisplay('7')">7</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('8')">8</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('9')">9</button>
                    <button class="btn btn-danger col" onclick="clearDisplay()">C</button>
                </div>
                <div class="row mb-2">
                    <button class="btn btn-light col" onclick="appendToDisplay('4')">4</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('5')">5</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('6')">6</button>
                    <button class="btn btn-warning col" onclick="appendToDisplay('/')">/</button>
                </div>
                <div class="row mb-2">
                    <button class="btn btn-light col" onclick="appendToDisplay('1')">1</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('2')">2</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('3')">3</button>
                    <button class="btn btn-warning col" onclick="appendToDisplay('*')">*</button>
                </div>
                <div class="row">
                    <button class="btn btn-light col" onclick="appendToDisplay('0')">0</button>
                    <button class="btn btn-light col" onclick="appendToDisplay('.')">.</button>
                    <button class="btn btn-success col" onclick="calculateResult()">=</button>
                    <button class="btn btn-warning col" onclick="appendToDisplay('-')">-</button>
                </div>
                <div class="row mt-2">
                    <button class="btn btn-warning col" onclick="appendToDisplay('+')">+</button>
                </div>
            </div>
        </div>
    </div>
<script>
    const toggleCalculatorButton = document.getElementById('toggle-calculator');
    const calculatorContainer = document.getElementById('calculator-container');
    const calcDisplay = document.getElementById('calc-display');

    toggleCalculatorButton.addEventListener('click', function () {
        if (calculatorContainer.style.display === 'none' || calculatorContainer.style.display === '') {
            calculatorContainer.style.display = 'block';
            toggleCalculatorButton.innerHTML = '<i class="fas fa-calculator"></i> Hide Calculator';
        } else {
            calculatorContainer.style.display = 'none';
            toggleCalculatorButton.innerHTML = '<i class="fas fa-calculator"></i> Show Calculator';
        }
    });

    function appendToDisplay(value) {
        if (calcDisplay.value === '0') {
            calcDisplay.value = value;
        } else {
            calcDisplay.value += value;
        }
    }

    function clearDisplay() {
        calcDisplay.value = '0';
    }

    function calculateResult() {
        try {
            calcDisplay.value = eval(calcDisplay.value);
        } catch (error) {
            calcDisplay.value = 'Error';
        }
    }
</script>