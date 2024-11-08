<?php
// Enable error reporting for debugging (disable in production)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust the path as necessary based on your project structure
require '/opt/lampp/htdocs/hall/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email from POST data
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($email)) {
        echo json_encode(["success" => false, "message" => "Email is required."]);
        exit();
    }

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP, email, and generation time in session
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['otp_generated_time'] = time(); // Store generation time

    // Configure PHPMailer to use Gmail SMTP
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'likhonhere007@gmail.com';  // Replace with your Gmail address
        $mail->Password = 'fwpmuhqpukunfymd';           // App password generated from Google
        $mail->SMTPSecure = 'tls';                      // 'tls' or 'ssl'
        $mail->Port = 587;                              // 587 for TLS, 465 for SSL

        $mail->setFrom('likhonhere007@gmail.com', 'Likhon'); // Replace with your Gmail address and name
        $mail->addAddress($email); // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'University Hall Ragistration OTP Code';
        $mail->Body    = "Your University Hall Ragistration OTP code is: <b>$otp</b>";

        $mail->send();

        // Respond with success
        echo json_encode(["success" => true, "message" => "OTP sent to your email."]);
        exit();
    } catch (Exception $e) {
        // Log the error to the server logs
        error_log("PHPMailer Error: " . $mail->ErrorInfo);

        // Respond with failure
        echo json_encode(["success" => false, "message" => "Failed to send OTP: " . $mail->ErrorInfo]);
        exit();
    }
}
?>
