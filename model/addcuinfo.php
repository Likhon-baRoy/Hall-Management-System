<?php
include '../db/config.php';

// Enable detailed error reporting (useful in development, disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to validate email using ZeroBounce API
function validate_email($email) {
    $apiKey = 'e68e4a05150845f49605a8dce4603599';  // Replace with your actual API key
    $apiUrl = "https://api.zerobounce.net/v2/validate?api_key={$apiKey}&email={$email}";

    // Initialize curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Execute request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode response
    $data = json_decode($response, true);

    // Return true if email is valid, false otherwise
    return isset($data['status']) && $data['status'] === 'valid';
}

// Check if connected to the database
if ($myconnect->connect_error) {
    die("Connection failed: " . $myconnect->connect_error);
} else {
    echo "Connected to the database successfully.<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize form data
     $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Backend email validation using ZeroBounce
    if (!validate_email($email)) {
        die("Error: Invalid email address. Please enter a valid email.");
    }

    // Other form data processing...
    $uid = intval($_POST['uid']);
    $username = strtolower(trim($_POST['username']));  // Convert to lowercase, trim spaces

    // Validate that username contains only lowercase letters and numbers
    if (!preg_match('/^[a-z0-9]+$/', $username)) {
        die("Error: Username can only contain lowercase letters and numbers.");
    }

    // Use htmlspecialchars() or strip_tags() to sanitize user input instead of deprecated filters
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $birthdate = $_POST['birthdate'];  // Assumes valid format from HTML5 date input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $hometown = htmlspecialchars(trim($_POST['hometown']));
    $move_in_date = $_POST['move_in_date'];  // Assumes valid format from HTML5 date input

    // Fetch predefined values from form
    $role = strtolower(trim($_POST['role']));  // Convert role to lowercase
    $gender = strtolower(trim($_POST['gender']));
    $preferred_hall = strtolower(trim($_POST['preferred_hall']));
    $room_type = strtolower(trim($_POST['room_type']));

    // Validate predefined values

    // Ensure valid role
    $valid_roles = ['student', 'administrator', 'staff'];
    if (!in_array($role, $valid_roles)) {
        die("Error: Invalid role selected.");
    }

    // Ensure valid gender
    $valid_genders = ['male', 'female', 'other'];
    if (!in_array($gender, $valid_genders)) {
        die("Error: Invalid gender selected.");
    }

    // Ensure valid room type
    $valid_room_types = ['single', 'double', 'suite'];
    if (!in_array($room_type, $valid_room_types)) {
        die("Error: Invalid room type selected.");
    }

    // Ensure valid preferred hall
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

    // Ensure password meets security requirements (e.g., length)
    if (strlen($password) < 8) {
        die("Error: Password must be at least 8 characters long.");
    }

    // Hash the password for security
    $encoded_password = password_hash($password, PASSWORD_DEFAULT);

    // Define the action variable (set to 1 by default)
    $action = 1;

    // Step 1: Check if the UID or Username already exists
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

    // Prepare the SQL query to insert user data
    $sql = "INSERT INTO c_info (uid, username, first_name, last_name, birthdate, gender, email, phone, hometown, preferred_hall, room_type, move_in_date, password, action, role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $myconnect->prepare($sql)) {
        // Correctly bind all 15 parameters: 1 integer for uid, 1 integer for action, and 12 strings
        if (!$stmt->bind_param('issssssssssssis', $uid, $username, $first_name, $last_name, $birthdate, $gender, $email, $phone, $hometown, $preferred_hall, $room_type, $move_in_date, $encoded_password, $action, $role)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        // Execute the prepared statement
        if (!$stmt->execute()) {
            echo "Execution failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            echo "Data inserted successfully!";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $myconnect->error;
    }
}
?>
