<?php
// import.php
// This script handles importing data from a CSV file into the 'warga' table.

require 'session_check.php';
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];
    $file_ext = pathinfo($_FILES["csv_file"]["name"], PATHINFO_EXTENSION);

    if (strtolower($file_ext) == 'csv') {
        if (($handle = fopen($file, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            $imported_count = 0;
            $error_count = 0;

            $sql = "INSERT INTO warga (nama, tempat_lahir, tanggal_lahir, nik, kk, no_rumah, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $nama = $data[0] ?? '';
                $tempat_lahir = $data[1] ?? '';
                $tanggal_lahir = $data[2] ?? '';
                $nik = $data[3] ?? '';
                $kk = $data[4] ?? '';
                $no_rumah = $data[5] ?? '';
                $no_hp = $data[6] ?? '';
                $alamat = $data[7] ?? '';

                if (empty($nik) && empty($nama)) {
                    continue;
                }

                try {
                    $stmt->execute([$nama, $tempat_lahir, $tanggal_lahir, $nik, $kk, $no_rumah, $no_hp, $alamat]);
                    $imported_count++;
                } catch (PDOException $e) {
                    $error_count++;
                }
            }
            fclose($handle);
            $message = "<div class='alert alert-success'>Import Selesai. Berhasil: {$imported_count}, Gagal (kemungkinan data duplikat): {$error_count}</div>";
        } else {
            $message = "<div class='alert alert-danger'>Gagal membuka file yang diunggah.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Format file tidak valid. Harap unggah file .csv</div>";
    }
}

include 'header.php';
?>

<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Import Data Warga dari CSV</h3>
    </div>
    <div class="card-body">
        <?php echo $message; ?>
        <p>Silakan unggah file CSV (.csv) dengan format kolom berikut:</p>
        <ol>
            <li><strong>Kolom 1:</strong> Nama</li>
            <li><strong>Kolom 2:</strong> Tempat Lahir</li>
            <li><strong>Kolom 3:</strong> Tanggal Lahir (Format: YYYY-MM-DD)</li>
            <li><strong>Kolom 4:</strong> NIK</li>
            <li><strong>Kolom 5:</strong> No. Kartu Keluarga</li>
            <li><strong>Kolom 6:</strong> No. Rumah</li>
            <li><strong>Kolom 7:</strong> No. HP</li>
            <li><strong>Kolom 8:</strong> Alamat</li>
        </ol>
        <p><strong>Penting:</strong> Baris pertama dalam file CSV harus berisi header dan akan dilewati.</p>
        
        <form action="import.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csv_file">Pilih File CSV</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="csv_file" name="csv_file" required accept=".csv">
                        <label class="custom-file-label" for="csv_file">Choose file</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Upload dan Import</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php
include 'footer.php';
?>
