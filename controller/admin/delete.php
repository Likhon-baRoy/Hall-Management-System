// delete.php
include '../../config/config.php';

// Check if 'uid' is provided
if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];

    // Sanitize the input
    $uid = mysqli_real_escape_string($myconnect, $uid);

    // Perform the delete query
    $deleteQuery = "DELETE FROM c_info WHERE uid = '$uid'";

    if (mysqli_query($myconnect, $deleteQuery)) {
        echo json_encode(['success' => true]);
        header("Location: admin.php");  // Redirect to admin page or another page after deletion
        exit();
    } else {
        echo json_encode(['success' => false]);
        echo "An error occurred while deleting the record.";
    }
} else {
    echo "Invalid user ID.";
}

// Close connection
mysqli_close($myconnect);

// Add this after the delete logic:
echo "<script>window.location.reload();</script>";
