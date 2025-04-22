<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Log the POST data
error_log("Form submission received: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $name = $_POST['contact-name'] ?? '';
        $phone = $_POST['contact-phone'] ?? '';
        $email = $_POST['contact-email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['contact-message'] ?? '';

        // Validate inputs
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception('Please fill in all required fields');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'upadhyay2000prabhat@gmail.com'; // Your Gmail address
        $mail->Password = 'kwcc ubgf dnbu uvjd'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('upadhyay2000prabhat@gmail.com'); // Your email address
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Portfolio Contact Form: $subject";
        $mail->Body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";
        $mail->AltBody = "
            New Contact Form Submission\n\n
            Name: {$name}\n
            Phone: {$phone}\n
            Email: {$email}\n
            Subject: {$subject}\n
            Message:\n{$message}
        ";

        // Send email
        $mail->send();
        echo json_encode([
            'status' => 'success',
            'message' => 'Message sent successfully!'
        ]);

    } catch (Exception $e) {
        error_log("Error in mail.php: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => "Message could not be sent. Mailer Error: {$e->getMessage()}"
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?> 