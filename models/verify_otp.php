<?php
session_start();

// Set the response header to JSON
header("Content-Type: application/json");

// Function to send JSON response and exit
function sendResponse($success, $message) {
    echo json_encode(["success" => $success, "message" => $message]);
    exit();
}

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request method.");
}

// Retrieve POST parameters
$otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validate inputs
if (empty($otp) || empty($email)) {
    sendResponse(false, "OTP and email are required.");
}

// Check if OTP, email, and generation time are set in the session
if (!isset($_SESSION['otp']) || !isset($_SESSION['email']) || !isset($_SESSION['otp_generated_time'])) {
    sendResponse(false, "Session expired or OTP not set. Please request a new OTP.");
}

// Verify that the email matches the one in the session
if ($_SESSION['email'] !== $email) {
    sendResponse(false, "Email does not match the OTP request.");
}

// Define OTP expiry time in seconds (e.g., 5 minutes)
$otp_expiry_time = 180; // 3 minutes
$current_time = time();
$otp_generated_time = $_SESSION['otp_generated_time'];

// Check if OTP has expired
if (($current_time - $otp_generated_time) > $otp_expiry_time) {
    // OTP has expired
    unset($_SESSION['otp'], $_SESSION['email'], $_SESSION['otp_generated_time']);
    sendResponse(false, "OTP has expired. Please request a new OTP.");
}

// Verify that the OTP matches the one in the session
if ($_SESSION['otp'] !== $otp) {
    sendResponse(false, "Invalid OTP. Please check the OTP sent to your email.");
}

// OTP is valid; clear the session variables
unset($_SESSION['otp'], $_SESSION['email'], $_SESSION['otp_generated_time']);

// Respond with success
sendResponse(true, "OTP verified successfully.");
