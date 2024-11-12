<?php
session_start();
include '../config/config.php';

// Set the response header to JSON
header("Content-Type: application/json");

// Retrieve the email from the POST request
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email)) {
    echo json_encode(["success" => false, "message" => "Email is required."]);
    exit();
}

// Validate email format server-side
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit();
}

// Prepare SQL query to check if the email exists
$sql = "SELECT email FROM c_info WHERE email = ?";
$stmt = $myconnect->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if email is already in use
if ($stmt->num_rows > 0) {
    // Email is already in use
    echo json_encode(["success" => false, "message" => "Email is already in use. Please use another email."]);
} else {
    // Email is available
    echo json_encode(["success" => true, "message" => "Email is available."]);
}

$stmt->close();
$myconnect->close();
?>
