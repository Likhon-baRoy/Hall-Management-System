<?php
include '../config/config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Check if the connection is established
    if (!$myconnect) {
        echo "Database connection error";
        exit;
    }

    // Prepare and execute the query to check for the username
    $stmt = $myconnect->prepare("SELECT uid FROM c_info WHERE username = ?");
    if ($stmt === false) {
        echo "Failed to prepare statement: " . $myconnect->error;
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists in database
    if ($stmt->num_rows > 0) {
        echo "taken"; // Username already exists
    } else {
        echo "available"; // Username is available
    }

    $stmt->close();
    $myconnect->close();
}
