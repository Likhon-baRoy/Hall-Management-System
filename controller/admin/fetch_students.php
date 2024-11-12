<?php
include '../../config/config.php';

// Get filter and search criteria from the GET request
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query based on filter and search input
$sql = "SELECT * FROM c_info";
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

// Execute the query
$result = mysqli_query($myconnect, $sql);

// Generate HTML rows for each user
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['uid']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['role']}</td>
                <td>
                  <a href='edit.php?uid={$row['uid']}' class='btn btn-warning' onclick='return confirmAction('edit')'>Edit</a>
                  <a href='delete.php?uid={$row['uid']}' class='btn btn-danger' onclick='return confirmAction('delete')'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No users found.</td></tr>";
}

mysqli_close($myconnect);
?>
