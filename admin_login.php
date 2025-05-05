<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TruTally Admin Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
    body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right,rgb(2, 110, 85),rgb(76, 175, 145)); 
        }
        .logo {
            font-size: 28px;
            font-weight: 600;
            color:rgb(250, 251, 253);
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to right,rgb(2, 110, 85),rgb(76, 175, 145)); 
            margin-top: 150px;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color:rgb(27, 150, 58);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color:rgb(30, 188, 64);
        }
        .form-container p {
            text-align: center;
            margin-top: 10px;
        }
        .form-container p a {
            color: #224abe;
            text-decoration: none;
        }
        .form-container p a:hover {
            text-decoration: underline;
        }
        .form-container .alert {
            margin-top: 10px;
            text-align: center;
        }
        .form-container .alert-success {
            color: green;
        }
        .form-container .alert-danger {
            color: red;
        }
        .form-container .alert-warning {
            color: orange;
        }
        .form-container .alert-info {
            color: blue;
        }
        .form-container .alert-primary {
            color: #007bff;
        }
        .form-container .alert-secondary {
            color: #6c757d;
        }
        .form-container .alert-light {
            color: #f8f9fa;
        }
        .form-container .alert-dark {
            color: #343a40;
        }
        .form-container .alert-link {
            color: #007bff;
        }
        .form-container .alert-link:hover {
            text-decoration: underline;
        }
        .form-container .alert-link:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
</style>
<body>
    <form method="POST" action="admin_login.php" class="form-container">
    <div class="logo">TrueTally Admin</div>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <div style="text-align: center;">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>