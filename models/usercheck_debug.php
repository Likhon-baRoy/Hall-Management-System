
<?php
session_start();  // Start the session
include("../config/config.php");

// Fetch and sanitize the form data
$username = strtolower(trim($_POST['username']));
$password = $_POST['password'];
$role = strtolower(trim($_POST['role']));

// Debugging: Check input values
error_log("Debug: Received Username - $username, Role - $role");

// Prepare the SQL query to check the username and role
$sql = "SELECT * FROM c_info WHERE username = ? AND role = ?";
if ($stmt = $myconnect->prepare($sql)) {
    // Bind parameters and execute the query
    $stmt->bind_param('ss', $username, $role);
    if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();
        
        // Debugging: Check if the query returns a row
        error_log("Debug: Rows matched - " . $result->num_rows);

        // Check if the user exists
        if ($result->num_rows == 1) {
            // Fetch the user's data
            $user = $result->fetch_assoc();

            // Verify the password using password_verify()
            if (password_verify($password, $user['password'])) {
                // Store user data in session variables
                $_SESSION['user_id'] = $user['uid'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Debugging: Successful login
                error_log("Debug: Login successful for user - $username");
                echo "Login successful.";
            } else {
                // Debugging: Password mismatch
                error_log("Debug: Password mismatch for user - $username");
                echo "Password incorrect. Please try again.";
            }
        } else {
            // Debugging: No matching user found
            error_log("Debug: No matching user found for Username - $username, Role - $role");
            echo "Username or Role incorrect. Please try again.";
        }
    } else {
        // Debugging: Execution error
        error_log("Debug: Query execution failed - " . $stmt->error);
        echo "An error occurred. Please try again.";
    }
    $stmt->close();
} else {
    // Debugging: Statement preparation error
    error_log("Debug: Statement preparation failed - " . $myconnect->error);
    echo "An error occurred. Please try again.";
}
?>
