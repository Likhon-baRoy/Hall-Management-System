<?php
include("../db/config.php");

// Fetch the form data
$username = strtolower(trim($_POST['username']));
$password = $_POST['password'];
$role = strtolower(trim($_POST['role']));  // Sanitize the role

// Prepare the SQL query to check the username and role
$sql = "SELECT * FROM c_info WHERE username = ? AND role = ?";
if ($stmt = $myconnect->prepare($sql)) {
    // Bind the parameters (username and role) and execute the query
    $stmt->bind_param('ss', $username, $role);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify the password using password_verify()
        if (password_verify($password, $user['password'])) {
            // Success: Redirect and show a success message
            echo '<script>alert("Successfully Logged In!");</script>';
            echo '<script>window.location.href = "../index.php";</script>';
        } else {
            // Incorrect password
            echo '<script>alert("Incorrect password. Please try again.");</script>';
            echo '<script>window.location.href = "../login.html";</script>';
        }
    } else {
        // Username or role doesn't exist or mismatch
        echo '<script>alert("Username or Role incorrect. Please try again.");</script>';
        echo '<script>window.location.href = "../login.html";</script>';
    }

    // Close the statement
    $stmt->close();
} else {
    // Error with SQL statement
    echo "Error: Could not prepare the SQL statement.";
}

// Close the database connection
$myconnect->close();
?>
