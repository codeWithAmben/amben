<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://i.postimg.cc/VsjLDnqn/blog-cover-finance-processes-and-templates.webp');
            backdrop-filter: blur(3px);
        }
        .floating-element {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1000;
        }
        .floating-element:hover {
            background-color: #218838;
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
            text-shadow: 2px 0px 2px rgb(32, 109, 11); /* Added shadow */
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
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%; z-index: 1;">
            <h2 class="logo">TrueTally</h2>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                    <small id="email-validation-message" class="text-muted">Enter a valid email address</small>
                </div>
                <div class="mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                    <div class="progress mt-2" style="height: 5px;">
                        <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                    </div>
                    <small id="password-strength-message" class="text-muted">Enter a strong password</small>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100">Register</button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="login.php" class="text-success">Login here</a></p>
        </div>
    </div>
    <div class="floating-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: radial-gradient(circle, rgba(76,175,80,0.2) 0%, rgba(255,255,255,0) 70%);"></div>
    <div class="floating-element position-absolute" style="top: 10%; left: 5%; width: 80px; height: 80px; background: rgba(76, 175, 80, 0.3); border-radius: 50%; animation: float1 5s infinite;"></div>
    <div class="floating-element position-absolute" style="bottom: 15%; right: 10%; width: 50px; height: 50px; background: rgba(2, 110, 85, 0.3); border-radius: 50%; animation: float2 6s infinite;"></div>
    <div class="floating-element position-absolute" style="top: 20%; right: 15%; width: 100px; height: 100px; background: rgba(255, 193, 7, 0.3); border-radius: 50%; animation: float3 7s infinite;"></div>

  <!-- Help Button -->
<div class="floating-element" data-bs-toggle="modal" data-bs-target="#helpModal">
    <i class="fas fa-question-circle"></i> Help
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Need Help?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="help_handler.php" method="POST">
                    <div class="mb-3">
                        <label for="helpName" class="form-label">Your Name</label>
                        <input type="text" name="name" id="helpName" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="helpEmail" class="form-label">Your Email</label>
                        <input type="email" name="email" id="helpEmail" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="helpMessage" class="form-label">Your Message</label>
                        <textarea name="message" id="helpMessage" class="form-control" rows="4" placeholder="Describe your issue or question" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                    <div class="mt-3">
                        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#chatbotModal">Chat with Bot</button>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 

                    <!-- Chatbot Modal -->
                    <div class="modal fade" id="chatbotModal" tabindex="-1" aria-labelledby="chatbotModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="chatbotModalLabel">Chat with TrueTally Bot</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="chatbot-conversation" style="height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                                        <p><strong>Bot:</strong> Hi! How can I assist you today?</p>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" id="chatbot-input" class="form-control" placeholder="Type your message here...">
                                        <button id="chatbot-send" class="btn btn-primary">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   

                    <script>
                        const chatbotConversation = document.getElementById('chatbot-conversation');
                        const chatbotInput = document.getElementById('chatbot-input');
                        const chatbotSend = document.getElementById('chatbot-send');

                        chatbotSend.addEventListener('click', () => {
                            const userMessage = chatbotInput.value.trim();
                            if (userMessage) {
                                const userMessageElement = document.createElement('p');
                                chatbotConversation.appendChild(userMessageElement);

                                let botResponse = '';
                                if (userMessage.toLowerCase().includes('register')) {
                                    botResponse = 'To register, fill out the form with your username, email, and password, then click the Register button.';
                                } else if (userMessage.toLowerCase().includes('system')) {
                                    botResponse = 'This system is TrueTally, a platform designed to help you manage your tasks and accounts efficiently.';
                                } else {
                                    botResponse = 'I am here to help with registration and system-related queries. Please ask specific questions.';
                                }

                                const botMessageElement = document.createElement('p');
                                botMessageElement.innerHTML = `<strong>Bot:</strong> ${botResponse}`;
                                chatbotConversation.appendChild(botMessageElement);

                                chatbotConversation.scrollTop = chatbotConversation.scrollHeight;
                                chatbotInput.value = '';
                            }
                        });

                        chatbotInput.addEventListener('keypress', (e) => {
                            if (e.key === 'Enter') {
                                chatbotSend.click();
                            }
                        });
                    </script>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $to = "lovesmy62@gmail.com"; // Replace with your support email
                        $subject = "Help Request from " . htmlspecialchars($_POST['name']);
                        $message = "Name: " . htmlspecialchars($_POST['name']) . "\n";
                        $message .= "Email: " . htmlspecialchars($_POST['email']) . "\n";
                        $message .= "Message: " . htmlspecialchars($_POST['message']) . "\n";
                        $headers = "From: " . htmlspecialchars($_POST['email']);

                        if (mail($to, $subject, $message, $headers)) {
                            echo "<script>alert('Your message has been sent successfully.');</script>";
                        } else {
                            echo "<script>alert('Failed to send your message. Please try again later.');</script>";
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        const emailInput = document.getElementById('email');
        const emailValidationMessage = document.getElementById('email-validation-message');

        emailInput.addEventListener('input', () => {
            const email = emailInput.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (emailRegex.test(email)) {
                emailValidationMessage.textContent = 'Valid email address';
                emailValidationMessage.className = 'text-success';
            } else {
                emailValidationMessage.textContent = 'Invalid email address';
                emailValidationMessage.className = 'text-danger';
            }
        });

        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('password-strength');
        const strengthMessage = document.getElementById('password-strength-message');

        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[\W_]/.test(password)) strength++;

            const strengthPercentage = (strength / 5) * 100;
            strengthBar.style.width = strengthPercentage + '%';
            if (strength <= 2) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthMessage.textContent = 'Weak password';
            } else if (strength === 3) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthMessage.textContent = 'Moderate password';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthMessage.textContent = 'Strong password';
            }

            // Disable the submit button if the password is not strong
            const submitButton = document.querySelector('button[type="submit"]');
            if (strength < 4) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        });
    </script>
</body>
</html>

<?php
include 'db_connection.php';

// Include PHPMailer classes (Composer autoload)
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure PHPMailer is installed via Composer
if (!class_exists(PHPMailer::class)) {
    die('PHPMailer is not installed. Run "composer require phpmailer/phpmailer" in your project directory.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Hash the email
    $hashed_email = hash('sha256', $email);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.');</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit();
    }

    // Check for duplicate email or username
    $checkUser = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $checkUser->bind_param("ss", $email, $username);
    $checkUser->execute();
    $checkUser->store_result();

    if ($checkUser->num_rows > 0) {
        echo "<script>alert('Email or username already exists.');</script>";
        $checkUser->close();
        exit();
    }
    $checkUser->close();

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_email, $hashed_password);

    if ($stmt->execute()) {
        // Send confirmation email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'truetally0@gmail.com';
            $mail->Password   = 'cgij xygq koud leha'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('truetally0@gmail.com', 'TrueTally');
            $mail->addAddress($email, $username); 

            $mail->isHTML(true);
            $mail->Subject = 'Welcome to TrueTally!';
            $mail->Body    = "Hi <b>$username</b>,<br><br>Thank you for registering at TrueTally.<br>You can now log in and start tracking your finances.<br><br>Best regards,<br>TrueTally Team";

            $mail->send();
            echo "<script>alert('Registration successful! Confirmation email sent.'); window.location.href='login.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Registration successful, but email could not be sent.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>