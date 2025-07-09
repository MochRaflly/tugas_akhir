<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil nilai tugas yang sudah dinilai
$sql = "SELECT p.*, t.judul as judul_tugas, mp.nama_mapel as nama_mapel
        FROM pengumpulan p 
        JOIN tugas t ON p.tugas_id = t.id 
        JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
        WHERE p.siswa_id = ? AND p.nilai IS NOT NULL
        ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Hitung statistik
$sql_stats = "SELECT 
                COUNT(*) as total_tugas,
                AVG(p.nilai) as rata_rata,
                MIN(p.nilai) as nilai_terendah,
                MAX(p.nilai) as nilai_tertinggi
              FROM pengumpulan p 
              WHERE p.siswa_id = ? AND p.nilai IS NOT NULL";

$stmt_stats = $conn->prepare($sql_stats);
$stmt_stats->bind_param("i", $user_id);
$stmt_stats->execute();
$stats_result = $stmt_stats->get_result();
$stats = $stats_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Saya - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📊 Nilai Saya</h2>
        
        <!-- Statistik Nilai -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo $stats['total_tugas'] ?? 0; ?></h3>
                <p>Total Tugas Dinilai</p>
            </div>
            <div class="stat-card">
                <h3><?php echo number_format($stats['rata_rata'] ?? 0, 1); ?></h3>
                <p>Rata-rata Nilai</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['nilai_terendah'] ?? 0; ?></h3>
                <p>Nilai Terendah</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['nilai_tertinggi'] ?? 0; ?></h3>
                <p>Nilai Tertinggi</p>
            </div>
        </div>
        
        <!-- Daftar Nilai -->
        <div class="panel">
            <h3>📋 Daftar Nilai Tugas</h3>
            
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tugas</th>
                            <th>Mata Pelajaran</th>
                            <th>Nilai</th>
                            <th>Komentar Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($nilai = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($nilai['judul_tugas']); ?></strong></td>
                                <td><?php echo htmlspecialchars($nilai['nama_mapel']); ?></td>
                                <td>
                                    <strong style="color: #28a745; font-size: 1.2em;"><?php echo $nilai['nilai']; ?></strong>
                                </td>
                                <td>
                                    <?php if ($nilai['komentar_guru']): ?>
                                        <?php echo htmlspecialchars($nilai['komentar_guru']); ?>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">
                    <h3>📊 Belum ada nilai</h3>
                    <p>Guru belum memberikan nilai untuk tugas yang sudah dikumpulkan.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Navigation -->
        <div class="nav">
            <a href="dashboard_siswa.php">🏠 Dashboard</a>
            <a href="daftar_tugas.php">📋 Tugas</a>
            <a href="daftar_mapel.php">📚 Mata Pelajaran</a>
            <a href="daftar_materi.php">📖 Materi</a>
            <a href="nilai_saya.php">📊 Nilai</a>
            <a href="profil.php">👤 Profil</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>
</body>
</html> 