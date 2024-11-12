<?php
// controllers/admin/edit.php
include '../config/config.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the current user data
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update user data
    $update_sql = "UPDATE users SET username = '$username', email = '$email', role = '$role' WHERE id = $user_id";
    mysqli_query($conn, $update_sql);
    header('Location: admin.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" value="<?= $user['username'] ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br>

        <label>Role:</label>
        <select name="role">
            <option value="Student" <?= $user['role'] === 'Student' ? 'selected' : '' ?>>Student</option>
            <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
        </select><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
