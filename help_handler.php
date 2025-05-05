<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    try {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO help_requests (name, email, message) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            // Send email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'truetally0@gmail.com'; 
                $mail->Password = 'cgij xygq koud leha';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('truetally0@gmail.com', 'Support Team');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Help Request Received';
                $mail->Body = "Dear $name,<br><br>Thank you for reaching out to us. We have received your message:<br><br><em>$message</em><br><br>We will get back to you shortly.<br><br>Best regards,<br>Support Team";

                $mail->send();
                echo "<script>alert('Your request has been submitted successfully! A confirmation email has been sent.'); window.location.href='register.php';</script>";
            } catch (Exception $e) {
                throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        } else {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    } finally {
        $conn->close();
    }
}
?>