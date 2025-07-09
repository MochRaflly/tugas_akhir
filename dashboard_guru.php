<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guru') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'];

// Ambil statistik
$sql_mapel = "SELECT COUNT(*) as total FROM mata_pelajaran";
$result_mapel = $conn->query($sql_mapel);
$total_mapel = $result_mapel->fetch_assoc()['total'];

$sql_materi = "SELECT COUNT(*) as total FROM materi";
$result_materi = $conn->query($sql_materi);
$total_materi = $result_materi->fetch_assoc()['total'];

$sql_tugas = "SELECT COUNT(*) as total FROM tugas";
$result_tugas = $conn->query($sql_tugas);
$total_tugas = $result_tugas->fetch_assoc()['total'];

$sql_pengumpulan = "SELECT COUNT(*) as total FROM pengumpulan";
$result_pengumpulan = $conn->query($sql_pengumpulan);
$total_pengumpulan = $result_pengumpulan->fetch_assoc()['total'];

// Ambil tugas terbaru
$sql_tugas_terbaru = "SELECT t.*, mp.nama_mapel as nama_mapel FROM tugas t 
                      JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                      ORDER BY t.deadline DESC LIMIT 5";
$result_tugas_terbaru = $conn->query($sql_tugas_terbaru);

// Ambil pengumpulan terbaru
$sql_pengumpulan_terbaru = "SELECT p.*, t.judul as judul_tugas, u.nama_lengkap as nama_siswa 
                            FROM pengumpulan p 
                            JOIN tugas t ON p.tugas_id = t.id 
                            JOIN users u ON p.siswa_id = u.id 
                            ORDER BY p.id DESC LIMIT 5";
$result_pengumpulan_terbaru = $conn->query($sql_pengumpulan_terbaru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>👨‍🏫 Dashboard Guru</h2>
        <p style="color: #666; margin-bottom: 30px;">Selamat datang, <?php echo htmlspecialchars($nama); ?>!</p>
        
        <!-- Statistik -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo $total_mapel; ?></h3>
                <p>Mata Pelajaran</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_materi; ?></h3>
                <p>Materi Pembelajaran</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_tugas; ?></h3>
                <p>Tugas Dibuat</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_pengumpulan; ?></h3>
                <p>Pengumpulan Tugas</p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="panel">
            <h3>⚡ Quick Actions</h3>
            <div class="quick-actions">
                <div class="action-card">
                    <h4>📚 Kelola Mata Pelajaran</h4>
                    <p>Tambahkan, edit, atau hapus mata pelajaran</p>
                    <a href="kelola_mapel.php" class="btn">Kelola Mapel</a>
                </div>
                <div class="action-card">
                    <h4>📖 Kelola Materi</h4>
                    <p>Upload dan kelola materi pembelajaran</p>
                    <a href="kelola_materi.php" class="btn">Kelola Materi</a>
                </div>
                <div class="action-card">
                    <h4>📝 Buat Tugas</h4>
                    <p>Buat tugas baru untuk siswa</p>
                    <a href="buat_tugas.php" class="btn">Buat Tugas</a>
                </div>
                <div class="action-card">
                    <h4>📊 Laporan Nilai</h4>
                    <p>Lihat dan kelola nilai siswa</p>
                    <a href="laporan_nilai.php" class="btn">Lihat Laporan</a>
                </div>
            </div>
        </div>
        
        <!-- Tugas Terbaru -->
        <div class="panel">
            <h3>📋 Tugas Terbaru</h3>
            <?php if ($result_tugas_terbaru->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Mata Pelajaran</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($tugas = $result_tugas_terbaru->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tugas['judul']); ?></td>
                                <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($tugas['deadline'])); ?></td>
                                <td>
                                    <?php 
                                    $deadline = new DateTime($tugas['deadline']);
                                    $now = new DateTime();
                                    if ($deadline < $now) {
                                        echo '<span class="status-badge status-terlambat">Selesai</span>';
                                    } else {
                                        echo '<span class="status-badge status-belum">Aktif</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Belum ada tugas yang dibuat</div>
            <?php endif; ?>
        </div>
        
        <!-- Pengumpulan Terbaru -->
        <div class="panel">
            <h3>📤 Pengumpulan Terbaru</h3>
            <?php if ($result_pengumpulan_terbaru->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tugas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pengumpulan = $result_pengumpulan_terbaru->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pengumpulan['nama_siswa']); ?></td>
                                <td><?php echo htmlspecialchars($pengumpulan['judul_tugas']); ?></td>
                                <td>
                                    <?php if ($pengumpulan['nilai']): ?>
                                        <span class="status-badge status-selesai">Dinilai</span>
                                    <?php else: ?>
                                        <span class="status-badge status-belum">Belum Dinilai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Belum ada pengumpulan tugas</div>
            <?php endif; ?>
        </div>
        
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