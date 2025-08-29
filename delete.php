<?php
// delete.php
// This script handles the deletion of a record.
// It takes an ID from the URL, prepares a DELETE statement, executes it,
// and then redirects the user back to the main page.

require 'db.php';

// Get the ID from the URL parameter.
$id = $_GET['id'] ?? null;

// If there is no ID, redirect back to the main page.
if (!$id) {
    header('Location: index.php');
    exit();
}

// Prepare and execute the SQL DELETE statement.
$sql = "DELETE FROM warga WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

// Redirect to the main page after deletion.
header("Location: index.php");
exit();
?>
