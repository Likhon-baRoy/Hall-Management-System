<?php
include '../../config/config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Panel Management System</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- jQuery library -->
</head>
<body>
    <div class="d-flex both">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard container">
            <h1 class="text-center">Dormitory User Management</h1>

            <!-- Filter Selection and Search Bar -->
            <?php include 'search_filter_form.php'; ?>

            <!-- Loading Spinner -->
            <div id="loading-spinner" style="display:none;">
                <img src="../../assets/img/spinner.gif" alt="Loading..." />
            </div>

            <!-- Load User Table -->
            <?php include 'user_table.php'; ?>
        </div>
    </div>

    <!-- JavaScript to load data dynamically with AJAX -->
    <script src="../../assets/js/admin.js"></script>
</body>
</html>
