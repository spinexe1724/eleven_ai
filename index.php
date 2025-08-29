<?php
// index.php
// This is the main page of the application.
// It reads and displays all the records from the 'warga' table.

// Check if the user is logged in, otherwise redirect to login page.
require 'session_check.php';

// Include the database connection file.
require 'db.php';

// Include the header file.
include 'header.php';

// Prepare and execute the SQL query to select all records from the 'warga' table.
$stmt = $pdo->query('SELECT * FROM warga ORDER BY nama ASC');
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Warga</h3>
        <div class="card-tools">
            <a href="import.php" class="btn btn-success"><i class="fas fa-file-import"></i> Import</a>
            <a href="export.php" class="btn btn-info"><i class="fas fa-file-export"></i> Export</a>
            <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="wargaTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>NIK</th>
                    <th>No. HP</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each record and display it in a table row.
                while($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['tempat_lahir']); ?></td>
                    <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($row['tanggal_lahir']))); ?></td>
                    <td><?php echo htmlspecialchars($row['nik']); ?></td>
                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                    <td>
                        <!-- Edit Button -->
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <!-- Delete Button -->
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<?php
// Include the footer file.
include 'footer.php';
?>
