<?php
 // Include the database configuration file to connect to the database
 include '../../config/config.php';

 // Ensure database config is included
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

// Determine filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$sql = "SELECT * FROM c_info";  // Update the table name to c_info
if ($filter === 'students') {
    $sql .= " WHERE role = 'Student'";  // Filter for students
} elseif ($filter === 'staffs') {
    $sql .= " WHERE role = 'Staff'";  // Filter for staff
} elseif ($filter === 'admin') {
    $sql .= " WHERE role = 'Administrator'";  // Filter for admin
}

 // Execute the query
 $result = mysqli_query($myconnect, $sql);
 if (!$result) {
 die("Query failed: " . mysqli_error($myconnect));
 }
 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Management System</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  </head>
  <body>
    <div class="d-flex both">
      <!-- Sidebar -->
      <div class="sidebar">
        <h2>ADMIN</h2>
        <ul class="nav flex-column">
          <li><a href="#"><i class="fas fa-user"></i> Provost</a></li>
          <li><a href="#"><i class="fas fa-building"></i> Hall</a></li>
          <li><a href="#"><i class="fas fa-utensils"></i> Dining</a></li>
          <li><a href="view/cuview.php" class="active"><i class="fas fa-user-graduate"></i> Students</a></li>
          <li><a href="#"><i class="fas fa-users"></i> Staffs</a></li>
          <li><a href="#"><i class="fas fa-door-open"></i> Floors</a></li>
          <li><a href="#"><i class="fas fa-bed"></i> Rooms</a></li>
          <li><a href="#"><i class="fas fa-exclamation-triangle"></i> Facilities Problem</a></li>
          <li><a href="#"><i class="fas fa-envelope"></i> Messages</a></li>
          <li><a href="logout.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
      </div>

      <!-- Dashboard Content -->
      <div class="dashboard container">
        <h1 class="text-center">Dormitory Management System</h1>

        <!-- Manage Students Section in admin.php -->
        <div id="manage-students">
          <h2>Manage Students</h2>

        <!-- Filter Selection -->
        <form method="GET" action="admin.php">
          <label for="filter">Filter: </label>
          <select name="filter" id="filter" onchange="this.form.submit()">
            <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Users</option>
            <option value="students" <?= $filter === 'students' ? 'selected' : '' ?>>Students Only</option>
            <option value="staffs" <?= $filter === 'staffs' ? 'selected' : '' ?>>Staffs Only</option>
            <option value="admin" <?= $filter === 'admin' ? 'selected' : '' ?>>Admin Only</option>
          </select>
        </form>

          <!-- User Data Table -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Preferred Hall</th>
                <th>Room Type</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $row['uid'] ?></td> <!-- User ID -->
                <td><?= $row['username'] ?></td> <!-- Username -->
                <td><?= $row['preferred_hall'] ?></td> <!-- Preferred Hall -->
                <td><?= $row['room_type'] ?></td> <!-- Room Type -->
                <td><?= $row['action'] == 1 ? 'Active' : 'Inactive' ?></td> <!-- Status (Active/Inactive) -->
                <td>
                  <a href="edit.php?id=<?= $row['uid'] ?>" class="btn btn-warning edit-btn">Edit</a>
                  <a href="delete.php?id=<?= $row['uid'] ?>" class="btn btn-danger delete-btn" onclick="return confirmAction('delete')">Delete</a>
                </td>
              </tr>
              <?php endwhile; ?>
              <?php else: ?>
              <tr><td colspan="6">No users found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <script>
          function confirmAction(action) {
              return confirm('Are you sure you want to ' + action + ' this user?');
          }
        </script>

      </div>
    </div>

  </body>
</html>
