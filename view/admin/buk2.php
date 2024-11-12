<?php include '../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  </head>
  <body>
    <div class="d-flex both">
      <?php include 'sidebar.php'; ?>
      <div class="container">
        <h1>Admin Panel - User Management</h1>
        <?php include 'search_filter_form.php'; ?>
        <?php include 'user_table.php'; ?>
      </div>
    </div>
  </body>
</html>
