<?php
// logout.php
// This script handles the user logout process.

// Initialize the session.
session_start();

// Unset all of the session variables.
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to the login page.
header("location: login.php");
exit;
?>
