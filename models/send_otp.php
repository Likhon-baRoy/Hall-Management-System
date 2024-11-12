<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/opt/lampp/htdocs/hall/vendor/autoload.php';

// Load environment variables from the .env file in the project root. (__DIR__) will be the directory of `send_otp.php`
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // (__DIR__ . '/../') moves up one directory
$dotenv->load();

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
        // Configure PHPMailer using environment variables
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USERNAME'];
        $mail->Password   = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = 'tls'; // set to 'ssl' if required
        $mail->Port       = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($email);

        // Email content
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
