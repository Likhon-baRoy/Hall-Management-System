<?php
include '../../config/config.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query based on filter and search input
$sql = "SELECT uid, username, email, phone, role FROM c_info";
$conditions = [];

if ($filter !== 'all') {
    $conditions[] = "role = '" . mysqli_real_escape_string($myconnect, $filter) . "'";
}
if (!empty($search)) {
    $conditions[] = "uid = '" . mysqli_real_escape_string($myconnect, $search) . "'";
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = mysqli_query($myconnect, $sql);

// Generate HTML rows for each user
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['uid']) . "</td>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['role']) . "</td>
                <td>
                  <a href='edit.php?uid=" . htmlspecialchars($row['uid']) . "' class='btn btn-warning'>Edit</a>
                  <a href='delete.php?uid=" . htmlspecialchars($row['uid']) . "' class='btn btn-danger'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No users found.</td></tr>";
}

mysqli_close($myconnect);
?>
