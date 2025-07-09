<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'] ?? 'Siswa';

// Ambil statistik dengan error handling
try {
    $sql_tugas_total = "SELECT COUNT(*) as total FROM tugas";
    $result_tugas_total = $conn->query($sql_tugas_total);
    $total_tugas = $result_tugas_total->fetch_assoc()['total'];

    $sql_tugas_dikerjakan = "SELECT COUNT(*) as total FROM pengumpulan WHERE siswa_id = $user_id";
    $result_tugas_dikerjakan = $conn->query($sql_tugas_dikerjakan);
    $tugas_dikerjakan = $result_tugas_dikerjakan->fetch_assoc()['total'];

    $sql_tugas_selesai = "SELECT COUNT(*) as total FROM pengumpulan WHERE siswa_id = $user_id AND nilai IS NOT NULL";
    $result_tugas_selesai = $conn->query($sql_tugas_selesai);
    $tugas_selesai = $result_tugas_selesai->fetch_assoc()['total'];

    $sql_mapel_total = "SELECT COUNT(*) as total FROM mata_pelajaran";
    $result_mapel_total = $conn->query($sql_mapel_total);
    $total_mapel = $result_mapel_total->fetch_assoc()['total'];

    // Ambil tugas yang belum dikerjakan - gunakan nama kolom yang benar
    $sql_tugas_belum = "SELECT t.*, mp.nama as nama_mapel, 
                        CASE 
                            WHEN t.deadline < NOW() THEN 'terlambat'
                            ELSE 'ontime'
                        END as status_deadline
                        FROM tugas t 
                        JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                        WHERE t.id NOT IN (SELECT tugas_id FROM pengumpulan WHERE siswa_id = $user_id)
                        ORDER BY t.deadline ASC LIMIT 5";
    $result_tugas_belum = $conn->query($sql_tugas_belum);

    // Ambil tugas yang sudah dikerjakan
    $sql_tugas_sudah = "SELECT p.*, t.judul as judul_tugas, mp.nama as nama_mapel 
                        FROM pengumpulan p 
                        JOIN tugas t ON p.tugas_id = t.id 
                        JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
                        WHERE p.siswa_id = $user_id 
                        ORDER BY p.created_at DESC LIMIT 5";
    $result_tugas_sudah = $conn->query($sql_tugas_sudah);
    
} catch (Exception $e) {
    // Jika ada error, set nilai default
    $total_tugas = 0;
    $tugas_dikerjakan = 0;
    $tugas_selesai = 0;
    $total_mapel = 0;
    $result_tugas_belum = null;
    $result_tugas_sudah = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>👨‍🎓 Dashboard Siswa</h2>
        <p style="color: #666; margin-bottom: 30px;">Selamat datang, <?php echo htmlspecialchars($nama); ?>!</p>
        
        <!-- Statistik -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo $total_tugas; ?></h3>
                <p>Total Tugas</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $tugas_dikerjakan; ?></h3>
                <p>Tugas Dikerjakan</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $tugas_selesai; ?></h3>
                <p>Tugas Selesai</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_mapel; ?></h3>
                <p>Mata Pelajaran</p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="panel">
            <h3>⚡ Quick Actions</h3>
            <div class="quick-actions">
                <div class="action-card">
                    <h4>📋 Daftar Tugas</h4>
                    <p>Lihat semua tugas yang tersedia</p>
                    <a href="daftar_tugas.php" class="btn">Lihat Tugas</a>
                </div>
                <div class="action-card">
                    <h4>📚 Mata Pelajaran</h4>
                    <p>Lihat daftar mata pelajaran</p>
                    <a href="daftar_mapel.php" class="btn">Lihat Mapel</a>
                </div>
                <div class="action-card">
                    <h4>📖 Materi Pembelajaran</h4>
                    <p>Akses materi pembelajaran</p>
                    <a href="daftar_materi.php" class="btn">Lihat Materi</a>
                </div>
                <div class="action-card">
                    <h4>📊 Nilai Saya</h4>
                    <p>Lihat nilai tugas yang sudah dinilai</p>
                    <a href="nilai_saya.php" class="btn">Lihat Nilai</a>
                </div>
            </div>
        </div>
        
        <!-- Tugas yang Belum Dikerjakan -->
        <div class="panel">
            <h3>📝 Tugas yang Belum Dikerjakan</h3>
            <?php if ($result_tugas_belum && $result_tugas_belum->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Mata Pelajaran</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($tugas = $result_tugas_belum->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tugas['judul']); ?></td>
                                <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                                <td class="deadline <?php echo $tugas['status_deadline']; ?>">
                                    <?php echo date('d/m/Y H:i', strtotime($tugas['deadline'])); ?>
                                </td>
                                <td>
                                    <?php if ($tugas['status_deadline'] == 'terlambat'): ?>
                                        <span class="status-badge status-terlambat">Terlambat</span>
                                    <?php else: ?>
                                        <span class="status-badge status-belum">Belum Dikerjakan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="kerjakan_tugas.php?id=<?php echo $tugas['id']; ?>" class="btn btn-warning">Kerjakan</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Semua tugas sudah dikerjakan! 🎉</div>
            <?php endif; ?>
        </div>
        
        <!-- Tugas yang Sudah Dikerjakan -->
        <div class="panel">
            <h3>✅ Tugas yang Sudah Dikerjakan</h3>
            <?php if ($result_tugas_sudah && $result_tugas_sudah->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal Submit</th>
                            <th>Status</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($tugas = $result_tugas_sudah->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tugas['judul_tugas']); ?></td>
                                <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($tugas['created_at'])); ?></td>
                                <td>
                                    <?php if ($tugas['nilai']): ?>
                                        <span class="status-badge status-selesai">Sudah Dinilai</span>
                                    <?php else: ?>
                                        <span class="status-badge status-belum">Belum Dinilai</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($tugas['nilai']): ?>
                                        <strong><?php echo $tugas['nilai']; ?></strong>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Belum ada tugas yang dikerjakan</div>
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