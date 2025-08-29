<?php
// edit.php
require_once "session_check.php";
require_once "db.php";

$nama = $tempat_lahir = $tanggal_lahir = $nik = $kk = $no_rumah = $no_hp = $alamat = "";
$id = 0;
$errors = [];

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    
    $sql = "SELECT * FROM warga WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $nama = $row["nama"];
                $tempat_lahir = $row["tempat_lahir"];
                $tanggal_lahir = $row["tanggal_lahir"];
                $nik = $row["nik"];
                $kk = $row["kk"];
                $no_rumah = $row["no_rumah"];
                $no_hp = $row["no_hp"];
                $alamat = $row["alamat"];
            } else {
                header("location: index.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    unset($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nama = trim($_POST["nama"]);
    $tempat_lahir = trim($_POST["tempat_lahir"]);
    $tanggal_lahir = trim($_POST["tanggal_lahir"]);
    $nik = trim($_POST["nik"]);
    $kk = trim($_POST["kk"]);
    $no_rumah = trim($_POST["no_rumah"]);
    $no_hp = trim($_POST["no_hp"]);
    $alamat = trim($_POST["alamat"]);

    if (empty($nama)) { $errors[] = "Nama is required."; }
    // Add other validations as needed...

    if (empty($errors)) {
        $sql = "UPDATE warga SET nama = :nama, tempat_lahir = :tempat_lahir, tanggal_lahir = :tanggal_lahir, nik = :nik, kk = :kk, no_rumah = :no_rumah, no_hp = :no_hp, alamat = :alamat WHERE id = :id";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":nama", $nama, PDO::PARAM_STR);
            $stmt->bindParam(":tempat_lahir", $tempat_lahir, PDO::PARAM_STR);
            $stmt->bindParam(":tanggal_lahir", $tanggal_lahir, PDO::PARAM_STR);
            $stmt->bindParam(":nik", $nik, PDO::PARAM_STR);
            $stmt->bindParam(":kk", $kk, PDO::PARAM_STR);
            $stmt->bindParam(":no_rumah", $no_rumah, PDO::PARAM_STR);
            $stmt->bindParam(":no_hp", $no_hp, PDO::PARAM_STR);
            $stmt->bindParam(":alamat", $alamat, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Data warga berhasil diperbarui.";
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
                    <h1>Edit Data Warga</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Formulir Edit Data</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                     <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                    Simpan Perubahan
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

