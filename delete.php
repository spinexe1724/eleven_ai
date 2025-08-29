<?php
// delete.php
require 'session_check.php';
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit(); }

// (NEW) Fetch the name before deleting for the success message.
$sql_select = "SELECT nama FROM warga WHERE id = ?";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([$id]);
$warga = $stmt_select->fetch();
$nama = $warga ? $warga['nama'] : 'Data';

// Prepare and execute the SQL DELETE statement.
$sql_delete = "DELETE FROM warga WHERE id = ?";
$stmt_delete = $pdo->prepare($sql_delete);
$stmt_delete->execute([$id]);

// (NEW) Set success message.
$_SESSION['success_message'] = htmlspecialchars($nama) . " berhasil dihapus!";
header("Location: index.php");
exit();
?>
