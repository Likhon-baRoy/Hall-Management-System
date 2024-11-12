<?php
session_start();  // Start the session
include("../config/config.php");

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

if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
}

    // Check if the user exists
    if ($result->num_rows == 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify the password using password_verify()
        if (password_verify($password, $user['password'])) {
            // Store user data in session variables
            $_SESSION['user_id'] = $user['uid'];  // Use 'uid' as per table structure
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to index page
            header("Location: ../public/index.php");
            exit();
        } else {
            // Incorrect password
            echo '<script>alert("Incorrect password. Please try again.");</script>';
            echo '<script>window.location.href = "../view/auth/login.html";</script>';
        }
    } else {
        // Username or role doesn't exist or mismatch
        echo '<script>alert("Username or Role incorrect. Please try again.");</script>';
        echo '<script>window.location.href = "../view/auth/login.html";</script>';
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
