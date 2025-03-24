<?php
session_start(); // Start session

// Destroy the session to log out
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit();
?>
