<?php
session_start();
include '../config/config.php';

// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if connected to the database
if ($myconnect->connect_error) {
    // Log error to a file
    error_log("Connection failed: " . $myconnect->connect_error, 3, 'error_log.txt');
    die("Error: Unable to connect to the database.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate email format explicitly
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    $otp = $_POST['otp'];

    // Verify OTP
    if (!isset($_SESSION['otp']) || !isset($_SESSION['email']) || $_SESSION['otp'] != $otp || $_SESSION['email'] != $email) {
        die("Error: Invalid OTP. Please check the OTP sent to your email.");
    }

    // Regenerate session ID after OTP verification
    session_regenerate_id(true);

    // OTP is valid; proceed with inserting user data
    $uid = intval($_POST['uid']);
    $username = strtolower(trim($_POST['username']));

    // Validate that username contains only lowercase letters and numbers
    if (!preg_match('/^[a-z0-9]+$/', $username)) {
        die("Error: Username can only contain lowercase letters and numbers.");
    }

    // Sanitize other inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $birthdate = $_POST['birthdate'];
    $phone = htmlspecialchars(trim($_POST['phone']));
    $hometown = htmlspecialchars(trim($_POST['hometown']));
    $move_in_date = strtotime($_POST['move_in_date']); // Convert to timestamp
    if ($move_in_date === false || $move_in_date < time()) {
        die("Error: Move-in date cannot be in the past.");
    }

    // Convert move-in date from Unix timestamp to MySQL date format (YYYY-MM-DD)
    $move_in_date = strtotime($_POST['move_in_date']);
    if ($move_in_date === false || $move_in_date < time()) {
        die("Error: Move-in date cannot be in the past.");
    }
    $move_in_date = date('Y-m-d', $move_in_date);  // Convert to YYYY-MM-DD format

    // Fetch predefined values from form
    $role = strtolower(trim($_POST['role']));
    $gender = strtolower(trim($_POST['gender']));
    $preferred_hall = strtolower(trim($_POST['preferred_hall']));
    $room_type = strtolower(trim($_POST['room_type']));

    // Validate predefined values
    $valid_roles = ['student', 'administrator', 'staff'];
    if (!in_array($role, $valid_roles)) {
        die("Error: Invalid role selected.");
    }

    $valid_genders = ['male', 'female', 'other'];
    if (!in_array($gender, $valid_genders)) {
        die("Error: Invalid gender selected.");
    }

    $valid_room_types = ['single', 'double', 'suite'];
    if (!in_array($room_type, $valid_room_types)) {
        die("Error: Invalid room type selected.");
    }

    $valid_halls = ['mokbul hossain hall', 'fazlur rahman hall', 'fatema hall', 'mona hall'];
    if (!in_array($preferred_hall, $valid_halls)) {
        die("Error: Invalid hall selected.");
    }

    // Check if the passwords match
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    // Password strength validation
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[^\w]/', $password)) {
        die("Error: Password must be at least 8 characters long, with at least one uppercase letter, one number, and one special character.");
    }

    // Hash the password for security
    $encoded_password = password_hash($password, PASSWORD_DEFAULT);

    // Define the action variable (set to 1 by default)
    $action = 1;

    // Check if UID or Username already exists
    $check_sql = "SELECT uid, username FROM c_info WHERE uid = ? OR username = ?";
    if ($stmt = $myconnect->prepare($check_sql)) {
        $stmt->bind_param('is', $uid, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            die("Error: A user with this UID or username already exists.");
        }
        $stmt->close();
    }

    // Check if email already exists
    $check_email_sql = "SELECT email FROM c_info WHERE email = ?";
    if ($stmt = $myconnect->prepare($check_email_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            die("Error: Email is already in use. Please use another email.");
        }
        $stmt->close();
    }

    // Prepare the SQL query to insert user data
    $sql = "INSERT INTO c_info (uid, username, first_name, last_name, birthdate, gender, email, phone, hometown, preferred_hall, room_type, move_in_date, password, action, role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $myconnect->prepare($sql)) {
        // Correctly bind all 15 parameters
        if (!$stmt->bind_param('issssssssssssis', $uid, $username, $first_name, $last_name, $birthdate, $gender, $email, $phone, $hometown, $preferred_hall, $room_type, $move_in_date, $encoded_password, $action, $role)) {
            error_log("Binding failed: " . $stmt->error, 3, 'error_log.txt');
            die("Error: Unable to process your request.");
        }

        // Execute the prepared statement
        if (!$stmt->execute()) {
            error_log("Execution failed: (" . $stmt->errno . ") " . $stmt->error, 3, 'error_log.txt');
            die("Error: Unable to complete registration. Please try again.");
        } else {
            header("Location: ../view/auth/login.html");
            exit();
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $myconnect->error;
    }
}
?>
