<?php
// filepath: c:\xampp\htdocs\adbms_project\login.php

// Start the session at the very beginning
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']); // Trim spaces
    $password = $_POST['password'];

    // Hash the email (as done during registration)
    $hashed_email = hash('sha256', $email);

    // Fetch the user from the database using the hashed email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $hashed_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session and redirect
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: tracker_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.');</script>";
    } 
    
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            background-image: url('https://i.postimg.cc/VsjLDnqn/blog-cover-finance-processes-and-templates.webp');
            backdrop-filter: blur(3px);
        }
        .logo_head {
            font-size: 28px;
            font-weight: 600;
            color: #f5f5f5;
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: 600;
            color: rgb(31, 160, 2);
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 0px 2px rgb(29, 88, 13); /* Added shadow */
        }
        .header {
            background: linear-gradient(to right,rgb(2, 110, 85),rgb(76, 175, 145)); 
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }
        .header a:hover {
            text-decoration: underline;
        }
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
       
        </style>
    </head>
    <body>
        <div class="header">
        <div class="logo"><a href="index.php">TrueTally</a></div>
        <nav>
            <a href="home_login.php">Home</a>
            <a href="about_login.php">About</a>
        </nav>
        </div>

<body class="bg-light position-relative">
    <div class="floating-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: radial-gradient(circle, rgba(76,175,80,0.2) 0%, rgba(255,255,255,0) 70%);"></div>
    <div class="floating-element position-absolute" style="top: 10%; left: 5%; width: 100px; height: 100px; background: rgba(76, 175, 80, 0.3); border-radius: 50%; animation: float1 5s infinite;"></div>
    <div class="floating-element position-absolute" style="bottom: 15%; right: 10%; width: 150px; height: 150px; background: rgba(2, 110, 85, 0.3); border-radius: 50%; animation: float2 6s infinite;"></div>
    <div class="floating-element position-absolute" style="top: 20%; right: 15%; width: 120px; height: 120px; background: rgba(255, 193, 7, 0.3); border-radius: 50%; animation: float3 7s infinite;"></div>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <div class="logo">TrueTally</div>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <button type="submit" name="login" class="btn btn-success w-100">Login</button>
                <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-success">Register here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
