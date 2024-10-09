<?php
include '../db/config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Prepare and execute the query to check for the username
    $stmt = $myconnect->prepare("SELECT uid FROM c_info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "taken"; // Username already exists
    } else {
        echo "available"; // Username is available
    }

    $stmt->close();
    $myconnect->close();
}
