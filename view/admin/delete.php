<?php
// controllers/admin/delete.php
include '../config/config.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $delete_sql = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $delete_sql);
}

header('Location: admin.php');
exit();
?>
