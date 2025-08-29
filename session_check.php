<?php
// session_check.php
// This script checks if the user is logged in.
// It should be included at the beginning of every protected page.

// Start the session.
session_start();

// If the user is not logged in, redirect them to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
