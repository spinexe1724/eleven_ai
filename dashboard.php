<?php
// dashboard.php
require_once "session_check.php";
require_once "db.php";

// --- METRICS CALCULATION ---

// 1. Total Residents
$total_warga = $pdo->query("SELECT count(*) FROM warga")->fetchColumn();

// 2. Most Common Place of Birth
$stmt_tempat = $pdo->query("SELECT tempat_lahir, COUNT(tempat_lahir) AS jumlah FROM warga GROUP BY tempat_lahir ORDER BY jumlah DESC LIMIT 1");
$tempat_terbanyak = $stmt_tempat->fetch(PDO::FETCH_ASSOC);

// 3. Number of Households (based on unique KK)
$jumlah_kk = $pdo->query("SELECT COUNT(DISTINCT kk) FROM warga")->fetchColumn();

// 4. Recently Added Residents
$stmt_terbaru = $pdo->query("SELECT nama, tanggal_lahir FROM warga ORDER BY id DESC LIMIT 5");
$warga_terbaru = $stmt_terbaru->fetchAll(PDO::FETCH_ASSOC);

// 5. Age Distribution
$stmt_ages = $pdo->query("SELECT tanggal_lahir FROM warga");
$all_birthdates = $stmt_ages->fetchAll(PDO::FETCH_ASSOC);

$age_groups = [
    'Anak-anak (0-17)' => 0,
    'Dewasa (18-60)' => 0,
    'Lansia (>60)' => 0
];

foreach ($all_birthdates as $item) {
    if (!empty($item['tanggal_lahir'])) {
        try {
            $birthDate = new DateTime($item['tanggal_lahir']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;

            if ($age <= 17) {
                $age_groups['Anak-anak (0-17)']++;
            } elseif ($age <= 60) {
                $age_groups['Dewasa (18-60)']++;
            } else {
                $age_groups['Lansia (>60)']++;
            }
        } catch (Exception $e) {
            // Ignore invalid date formats
        }
    }
}

// Prepare data for Chart.js
$age_chart_labels = json_encode(array_keys($age_groups));
$age_chart_data = json_encode(array_values($age_groups));

?>
<?php include 'header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Warga</span>
                            <span class="info-box-number"><?php echo htmlspecialchars($total_warga); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-map-marker-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tempat Lahir Terbanyak</span>
                            <span class="info-box-number"><?php echo $tempat_terbanyak ? htmlspecialchars($tempat_terbanyak['tempat_lahir']) : 'N/A'; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-home"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Kepala Keluarga</span>
                            <span class="info-box-number"><?php echo htmlspecialchars($jumlah_kk); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Age Distribution Chart -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Distribusi Usia Warga</h3>
                        </div>
                        <div class="card-body" style="min-height: 300px;">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Recently Added Residents -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Warga Baru Ditambahkan</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <?php foreach ($warga_terbaru as $warga): ?>
                                <li class="item">
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title"><?php echo htmlspecialchars($warga['nama']); ?></a>
                                        <span class="product-description">
                                            Lahir: <?php echo htmlspecialchars($warga['tanggal_lahir']); ?>
                                        </span>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="index.php" class="uppercase">Lihat Semua Warga</a>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- (NEW) Page-specific script for Chart.js -->
<!-- (NEW) Load Chart.js library ONLY on this page -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ageLabels = <?php echo $age_chart_labels; ?>;
    const ageData = <?php echo $age_chart_data; ?>;
    
    // (FIX) Corrected 'd' to '2d' to get the correct rendering context
    const ctx = document.getElementById('ageChart').getContext('2d');
    const ageChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ageLabels,
            datasets: [{
                label: 'Jumlah Warga',
                data: ageData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(75, 192, 192, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Ensure y-axis shows whole numbers for people counts
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?php include 'footer.php'; ?>

