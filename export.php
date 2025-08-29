<?php
// export.php
// This script exports data from the 'warga' table to a CSV file.

require 'session_check.php';
require 'db.php';

$filename = 'data-warga-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// Set the table headers for the CSV file.
fputcsv($output, [
    'Nama', 
    'Tempat Lahir', 
    'Tanggal Lahir (YYYY-MM-DD)', 
    'NIK', 
    'No. Kartu Keluarga', 
    'No. Rumah', 
    'No. HP', 
    'Alamat'
]);

// Fetch data from the database.
$stmt = $pdo->query('SELECT nama, tempat_lahir, tanggal_lahir, nik, kk, no_rumah, no_hp, alamat FROM warga ORDER BY nama ASC');

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

exit();
?>
