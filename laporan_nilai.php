<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: login.php');
    exit;
}

$guru_id = $_SESSION['user_id'];
$error = '';

// Ambil mapel milik guru
$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE guru_id = $guru_id");
$mapel_id = isset($_GET['mapel_id']) ? intval($_GET['mapel_id']) : 0;
$mapel_data = null;
if ($mapel_id) {
    $mapel_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE id=$mapel_id AND guru_id=$guru_id"));
    if (!$mapel_data) {
        $error = 'Mata pelajaran tidak valid.';
    }
}

$nilai_result = null;
$tugas_list = [];
$siswa_list = [];
if ($mapel_data) {
    // Ambil semua tugas di mapel ini
    $tugas_result = mysqli_query($conn, "SELECT id, judul FROM tugas WHERE mapel_id = $mapel_id ORDER BY id");
    while ($t = mysqli_fetch_assoc($tugas_result)) {
        $tugas_list[] = $t;
    }
    // Ambil semua siswa yang pernah mengumpulkan tugas di mapel ini
    $siswa_result = mysqli_query($conn, "
        SELECT DISTINCT u.id, u.nama_lengkap
        FROM pengumpulan p
        JOIN tugas t ON p.tugas_id = t.id
        JOIN users u ON p.siswa_id = u.id
        WHERE t.mapel_id = $mapel_id
        ORDER BY u.nama_lengkap
    ");
    while ($s = mysqli_fetch_assoc($siswa_result)) {
        $siswa_list[] = $s;
    }
    // Ambil semua nilai
    $nilai_result = [];
    foreach ($siswa_list as $siswa) {
        $row = ['nama_lengkap' => $siswa['nama_lengkap']];
        foreach ($tugas_list as $tugas) {
            $nilaiq = mysqli_query($conn, "SELECT nilai FROM pengumpulan WHERE tugas_id = {$tugas['id']} AND siswa_id = {$siswa['id']}");
            $nilai = mysqli_fetch_assoc($nilaiq);
            $row[$tugas['id']] = $nilai ? $nilai['nilai'] : '-';
        }
        $nilai_result[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📊 Laporan Nilai Siswa</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Pilih Mata Pelajaran -->
        <div class="panel">
            <h3>📚 Pilih Mata Pelajaran</h3>
            <form method="get" class="form">
                <div class="form-group">
                    <label for="mapel_id">Mata Pelajaran:</label>
                    <select name="mapel_id" id="mapel_id" required onchange="this.form.submit()" class="form-control">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php while($m = mysqli_fetch_assoc($mapel_result)): ?>
                            <option value="<?php echo $m['id']; ?>" <?php if($mapel_id==$m['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($m['nama_mapel']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($mapel_data && $nilai_result !== null): ?>
            <!-- Statistik -->
            <div class="stats-container">
                <div class="stat-card">
                    <h3><?php echo count($siswa_list); ?></h3>
                    <p>Total Siswa</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo count($tugas_list); ?></h3>
                    <p>Total Tugas</p>
                </div>
                <div class="stat-card">
                    <h3><?php 
                        $total_nilai = 0;
                        $count_nilai = 0;
                        foreach ($nilai_result as $row) {
                            foreach ($tugas_list as $tugas) {
                                if ($row[$tugas['id']] !== '-') {
                                    $total_nilai += $row[$tugas['id']];
                                    $count_nilai++;
                                }
                            }
                        }
                        echo $count_nilai > 0 ? number_format($total_nilai / $count_nilai, 1) : '0';
                    ?></h3>
                    <p>Rata-rata Nilai</p>
                </div>
                <div class="stat-card">
                    <h3><?php 
                        $completed = 0;
                        foreach ($nilai_result as $row) {
                            foreach ($tugas_list as $tugas) {
                                if ($row[$tugas['id']] !== '-') {
                                    $completed++;
                                }
                            }
                        }
                        echo $completed;
                    ?></h3>
                    <p>Tugas Selesai</p>
                </div>
            </div>

            <!-- Tabel Nilai -->
            <div class="panel">
                <h3>📋 Laporan Nilai: <strong><?php echo htmlspecialchars($mapel_data['nama_mapel']); ?></strong></h3>
                
                <?php if (count($nilai_result) > 0): ?>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <?php foreach ($tugas_list as $tugas): ?>
                                        <th><?php echo htmlspecialchars($tugas['judul']); ?></th>
                                    <?php endforeach; ?>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nilai_result as $row): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['nama_lengkap']); ?></strong></td>
                                    <?php 
                                    $total_siswa = 0;
                                    $count_siswa = 0;
                                    foreach ($tugas_list as $tugas): 
                                        $nilai = $row[$tugas['id']];
                                        if ($nilai !== '-') {
                                            $total_siswa += $nilai;
                                            $count_siswa++;
                                        }
                                    ?>
                                        <td>
                                            <?php if ($nilai !== '-'): ?>
                                                <span style="color: #28a745; font-weight: 600;"><?php echo htmlspecialchars($nilai); ?></span>
                                            <?php else: ?>
                                                <span style="color: #6c757d;">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td>
                                        <?php 
                                        if ($count_siswa > 0) {
                                            $avg = $total_siswa / $count_siswa;
                                            echo '<strong style="color: #007bff;">' . number_format($avg, 1) . '</strong>';
                                        } else {
                                            echo '<span style="color: #6c757d;">-</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-message">
                        <h3>📊 Belum ada data nilai</h3>
                        <p>Belum ada siswa yang mengumpulkan tugas untuk mata pelajaran ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif($mapel_id): ?>
            <div class="alert alert-error">
                ❌ Tidak ada data nilai untuk mata pelajaran ini.
            </div>
        <?php else: ?>
            <div class="panel">
                <h3>📊 Pilih Mata Pelajaran</h3>
                <div class="empty-message">
                    <h3>📚 Silakan pilih mata pelajaran</h3>
                    <p>Pilih mata pelajaran di atas untuk melihat laporan nilai siswa.</p>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div class="nav">
            <a href="dashboard_guru.php">🏠 Dashboard</a>
            <a href="kelola_mapel.php">📚 Mata Pelajaran</a>
            <a href="kelola_materi.php">📖 Materi</a>
            <a href="buat_tugas.php">📝 Tugas</a>
            <a href="laporan_nilai.php">📊 Nilai</a>
            <a href="profil.php">👤 Profil</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>
</body>
</html> 