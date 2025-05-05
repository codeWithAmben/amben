<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - TrueTally</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right,rgb(2, 110, 85),rgb(76, 175, 145)); 
            color:rgb(248, 252, 255); /* Match the home page text color */
        }
        .about-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(to right,rgb(2, 110, 85),rgb(76, 175, 145)); 
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .about-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .about-header h1 {
            font-size: 2rem;
            font-weight: bold;
            color:rgb(250, 252, 254);
        }
        .about-content {
            line-height: 1.6;
        }
        .btn-primary {
            background-color:rgb(33, 181, 161);
            border-color: rgb(33, 181, 161);
        }
        .btn-primary:hover {
            background-color:rgb(36, 245, 196);
            border-color:none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="about-container">
            <div class="about-header">
                <h1>About TrueTally</h1>
            </div>
            <div class="about-content">
                <p>Welcome to <strong>TrueTally</strong>, your personal expense tracker! Our goal is to help you manage your finances effectively by providing a simple and intuitive platform to track your income and expenses.</p>
                <p>With TrueTally, you can:</p>
                <ul>
                    <li>Add and categorize your transactions as income or expenses.</li>
                    <li>View your total income, expenses, and balance at a glance.</li>
                    <li>Edit or delete transactions as needed.</li>
                    <li>Search for specific transactions using keywords.</li>
                </ul>
                <p>TrueTally is designed to make financial management easy and accessible for everyone. Whether you're budgeting for personal use or managing small business finances, TrueTally has you covered.</p>
                <p>Thank you for choosing TrueTally. We hope it helps you achieve your financial goals!</p>
            </div>
            <div class="text-center mt-4">
                <a href="tracker_dashboard.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>