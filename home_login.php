<!-- filepath: c:\xampp\htdocs\adbms_project\home_login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TrueTally</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right,rgb(2, 110, 85),rgb(76, 175, 145)); 
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .home-container {
            max-width: 600px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .home-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .home-content {
            line-height: 1.8;
            margin-bottom: 20px;
        }
        .home-content ul {
            list-style: none;
            padding: 0;
        }
        .home-content ul li {
            margin: 10px 0;
        }
        .btn {
            margin: 10px 5px;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color:rgb(33, 181, 161);
            border: none;
        }
        .btn-primary:hover {
            background-color:rgb(36, 245, 196);
        }
        .btn-secondary {
            background-color:rgb(156, 182, 27);
            border: none;
        }
        .btn-secondary:hover {
            background-color:rgb(180, 231, 25);
        }
        .btn-link {
            color: #fff;
            text-decoration: underline;
        }
        .btn-link:hover {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="home-container">
        <div class="home-header">
            <h1>Welcome to TrueTally</h1>
        </div>
        <div class="home-content">
            <p>TrueTally is your personal expense tracker designed to help you manage your finances effectively.</p>
            <ul>
                <li>Track your income and expenses.</li>
                <li>View your financial summary at a glance.</li>
                <li>Edit or delete transactions as needed.</li>
                <li>Search for specific transactions.</li>
            </ul>
            <p>Start managing your finances today and achieve your financial goals with TrueTally!</p>
        </div>
        <div>
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-secondary">Register</a>
            <p><a href="admin_login.php" class="btn btn-link">Admin</a></p>
        </div>
    </div>
    
</body>
</html>