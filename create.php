<?php
// create.php
require_once "session_check.php";
require_once "db.php";

$nama = $tempat_lahir = $tanggal_lahir = $nik = $kk = $no_rumah = $no_hp = $alamat = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $nama = trim($_POST["nama"]);
    $tempat_lahir = trim($_POST["tempat_lahir"]);
    $tanggal_lahir = trim($_POST["tanggal_lahir"]);
    $nik = trim($_POST["nik"]);
    $kk = trim($_POST["kk"]);
    $no_rumah = trim($_POST["no_rumah"]);
    $no_hp = trim($_POST["no_hp"]);
    $alamat = trim($_POST["alamat"]);

    if (empty($nama)) { $errors[] = "Nama is required."; }
    if (empty($tempat_lahir)) { $errors[] = "Tempat Lahir is required."; }
    if (empty($tanggal_lahir)) { $errors[] = "Tanggal Lahir is required."; }
    if (empty($nik)) { $errors[] = "NIK is required."; }
    if (empty($kk)) { $errors[] = "KK is required."; }

    if (empty($errors)) {
        $sql = "INSERT INTO warga (nama, tempat_lahir, tanggal_lahir, nik, kk, no_rumah, no_hp, alamat) VALUES (:nama, :tempat_lahir, :tanggal_lahir, :nik, :kk, :no_rumah, :no_hp, :alamat)";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":nama", $nama, PDO::PARAM_STR);
            $stmt->bindParam(":tempat_lahir", $tempat_lahir, PDO::PARAM_STR);
            $stmt->bindParam(":tanggal_lahir", $tanggal_lahir, PDO::PARAM_STR);
            $stmt->bindParam(":nik", $nik, PDO::PARAM_STR);
            $stmt->bindParam(":kk", $kk, PDO::PARAM_STR);
            $stmt->bindParam(":no_rumah", $no_rumah, PDO::PARAM_STR);
            $stmt->bindParam(":no_hp", $no_hp, PDO::PARAM_STR);
            $stmt->bindParam(":alamat", $alamat, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Data warga berhasil ditambahkan.";
                header("location: index.php");
                exit();
            } else {
                $errors[] = "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
    unset($pdo);
}
?>

<?php include 'header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Data Warga</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Formulir Data Warga</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="card-body">
                                <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" class="form-control" id="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tempat_lahir">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir" value="<?php echo htmlspecialchars($tempat_lahir); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nik">NIK</label>
                                            <input type="text" name="nik" class="form-control" id="nik" value="<?php echo htmlspecialchars($nik); ?>" required>
                                        </div>
                                     </div>
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="kk">KK</label>
                                            <input type="text" name="kk" class="form-control" id="kk" value="<?php echo htmlspecialchars($kk); ?>" required>
                                        </div>
                                     </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="no_rumah">No. Rumah</label>
                                            <input type="text" name="no_rumah" class="form-control" id="no_rumah" value="<?php echo htmlspecialchars($no_rumah); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="no_hp">No. HP</label>
                                            <input type="text" name="no_hp" class="form-control" id="no_hp" value="<?php echo htmlspecialchars($no_hp); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" class="form-control" id="alamat" rows="3"><?php echo htmlspecialchars($alamat); ?></textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                    Simpan
                                </button>
                                <a href="index.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    document.querySelector('form').addEventListener('submit', function() {
        var submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.querySelector('.spinner-border').style.display = 'inline-block';
        submitBtn.querySelector('span:not(.spinner-border)').textContent = 'Menyimpan...';
    });
</script>

<?php include 'footer.php'; ?>

