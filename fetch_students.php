<?php
include 'db/config.php';

// Query to fetch all student data
$sql = "SELECT uid, username, preferred_hall, room_type, action FROM c_info";
$result = mysqli_query($myconnect, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $status = ($row['action'] == 1) ? 'Active' : 'Inactive';
        echo "<tr>";
        echo "<td>{$row['uid']}</td>";
        echo "<td>{$row['username']}</td>";
        echo "<td>{$row['preferred_hall']}</td>";
        echo "<td>{$row['room_type']}</td>";
        echo "<td>{$status}</td>";
        echo "<td>
                <button class='btn btn-primary edit-btn' data-uid='{$row['uid']}'>Edit</button>
                <button class='btn btn-danger delete-btn' data-uid='{$row['uid']}'>Delete</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No students found</td></tr>";
}
?>
