<?php
// edit.php
// This script handles updating an existing record.

require 'session_check.php';
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit();
}

$sql = "SELECT * FROM warga WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$warga = $stmt->fetch();

if (!$warga) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $tempat_lahir = trim($_POST['tempat_lahir']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $nik = trim($_POST['nik']);
    $kk = trim($_POST['kk']);
    $no_rumah = trim($_POST['no_rumah']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    $sql = "UPDATE warga SET nama = ?, tempat_lahir = ?, tanggal_lahir = ?, nik = ?, kk = ?, no_rumah = ?, no_hp = ?, alamat = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$nama, $tempat_lahir, $tanggal_lahir, $nik, $kk, $no_rumah, $no_hp, $alamat, $id]);
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

include 'header.php';
?>

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Data Warga</h3>
    </div>
    <form action="edit.php?id=<?php echo $id; ?>" method="post">
        <div class="card-body">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($warga['nama']); ?>" required>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($warga['tempat_lahir']); ?>" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($warga['tanggal_lahir']); ?>" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" value="<?php echo htmlspecialchars($warga['nik']); ?>" required>
            </div>
            <div class="form-group">
                <label for="kk">No. Kartu Keluarga</label>
                <input type="text" class="form-control" id="kk" name="kk" value="<?php echo htmlspecialchars($warga['kk']); ?>" required>
            </div>
            <div class="form-group">
                <label for="no_rumah">No. Rumah</label>
                <input type="text" class="form-control" id="no_rumah" name="no_rumah" value="<?php echo htmlspecialchars($warga['no_rumah']); ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No. HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($warga['no_hp']); ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($warga['alamat']); ?></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php
include 'footer.php';
?>
