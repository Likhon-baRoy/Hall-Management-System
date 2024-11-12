<?php
session_start();
$_SESSION['test'] = "Session works!";
echo "Session set. Go to check_session.php to verify.";
?>
