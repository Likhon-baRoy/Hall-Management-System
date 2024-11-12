<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not authenticated
    header("Location: ../login.html");
    exit();
}

// Fetch user data from database using $_SESSION['user_id']
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

<div class="profile-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p><a href="profile_settings.php" title="Edit Profile">Profile Settings</a> | <a href="logout.php" title="Logout">Logout</a></p>
</div>

</body>
</html>
