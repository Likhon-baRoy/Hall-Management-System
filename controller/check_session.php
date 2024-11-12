<?php
session_start();
if (isset($_SESSION['test'])) {
    echo "Session is working: " . $_SESSION['test'];
} else {
    echo "Session not set.";
}
?>
