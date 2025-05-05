<?php
// filepath: c:\xampp\htdocs\adbms_project\index.php

session_start();

// Redirect to the dashboard if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: tracker_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueTally - Welcome</title>

    <!-- External CSS and Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Internal CSS -->
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://i.postimg.cc/VsjLDnqn/blog-cover-finance-processes-and-templates.webp');
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .navbar-brand {
            font-weight: 700;
            color: green;
        }
        .navbar-nav .nav-link {
            color: green;
            font-weight: 600;
        }
        .navbar-nav .nav-link:hover {
            color: darkgreen;
        }

        /* Welcome Container Styles */
        .welcome-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
            width: 90%;
            max-width: 500px;
        }
        .welcome-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: green;
        }
        .welcome-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #6c757d;
        }
        .btn-primary {
            background-color: green;
            border-color: green;
        }
        .btn-primary:hover {
            background-color: darkgreen;
            border-color: darkgreen;
        }

        /* Navbar Hover Effect */
        .nav-hover {
            position: relative;
            display: inline-block;
            text-decoration: none;
            overflow: hidden;
        }
        .nav-hover::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: green;
            bottom: 0;
            left: -100%;
            transition: left 0.3s ease-in-out;
        }
        .nav-hover:hover::after {
            left: 0;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 20px;
                margin-top: 80px;
            }
            .welcome-container h1 {
                font-size: 2rem;
            }
            .welcome-container p {
                font-size: 1rem;
            }
            header {
                padding: 10px 0;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
            .navbar-nav .nav-link {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-container {
                padding: 15px;
                margin-top: 60px;
            }
            .welcome-container h1 {
                font-size: 1.8rem;
            }
            .welcome-container p {
                font-size: 0.9rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textElement = document.getElementById('animated-text');
            const text = "Welcome to TrueTally";
            let index = 0;

            function typeAnimation() {
                if (index < text.length) {
                    textElement.textContent += text.charAt(index);
                    index++;
                    setTimeout(typeAnimation, 100);
                } else {
                    setTimeout(() => {
                        textElement.textContent = "";
                        index = 0;
                        typeAnimation();
                    }, 2000);
                }
            }

            textElement.textContent = "";
            typeAnimation();
        });
    </script>
</head>
<body>
    <!-- Header Section -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">TrueTally</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link nav-hover" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-hover" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-hover" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-hover" href="register.php">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Welcome Section -->
    <div class="welcome-container">
        <h1 style="color: green; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">
            <span id="animated-text">Welcome to TrueTally</span>
        </h1>
        <p style="color: black;">Your personal finance tracker to manage income and expenses effectively.</p>
        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="display:block; margin-top:20px; color:green; text-decoration:underline;">
            Terms and Conditions
        </a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#policyModal" style="display:block; margin-top:5px; color:green; text-decoration:underline;">
            Privacy Policy
        </a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#contactModal" style="display:block; margin-top:5px; color:green; text-decoration:underline;">
            Contact Support
        </a>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="color: #222;">
            <p>
                By using TrueTally, you agree to the following terms and conditions:
            </p>
            <ul>
                <li>Your data is stored securely and used only for the purpose of managing your finances within this application.</li>
                <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
                <li>TrueTally is provided "as is" without warranty of any kind. We are not liable for any loss or damage arising from your use of this system.</li>
                <li>Do not use TrueTally for any unlawful activities.</li>
                <li>We reserve the right to update these terms at any time. Continued use of the system constitutes acceptance of the new terms.</li>
            </ul>
            <p>
                If you have questions, please contact our support team.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="policyModalLabel">Privacy Policy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="color: #222;">
            <p>
                TrueTally values your privacy. This policy explains how we collect, use, and protect your information:
            </p>
            <ul>
                <li>We collect only the information necessary to provide our finance tracking services.</li>
                <li>Your data is stored securely and is not shared with third parties except as required by law.</li>
                <li>We use cookies only for session management and user experience improvement.</li>
                <li>You may request deletion of your account and data at any time by contacting support.</li>
                <li>We may update this policy; changes will be posted on this page.</li>
            </ul>
            <p>
                For questions or concerns about your privacy, please contact our support team.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Support Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="help_handler.php">
            <div class="modal-header">
              <h5 class="modal-title" id="contactModalLabel">Contact Support</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: #222;">
              <div class="mb-3">
                <label for="supportName" class="form-label">Name</label>
                <input type="text" class="form-control" id="supportName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="supportEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="supportEmail" name="email" required>
              </div>
              <div class="mb-3">
                <label for="supportMessage" class="form-label">Message</label>
                <textarea class="form-control" id="supportMessage" name="message" rows="3" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Send</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>