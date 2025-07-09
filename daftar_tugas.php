<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil semua tugas dengan status pengumpulan
$sql = "SELECT t.*, mp.nama_mapel as nama_mapel, 
        CASE 
            WHEN p.id IS NOT NULL THEN 'sudah_dikerjakan'
            WHEN t.deadline < NOW() THEN 'terlambat'
            ELSE 'belum_dikerjakan'
        END as status_tugas,
        p.nilai
        FROM tugas t 
        JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
        LEFT JOIN pengumpulan p ON t.id = p.tugas_id AND p.siswa_id = $user_id
        ORDER BY t.deadline ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📋 Daftar Tugas</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Judul Tugas</th>
                        <th>Mata Pelajaran</th>
                        <th>Deskripsi</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($tugas = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($tugas['judul']); ?></strong></td>
                            <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                            <td><?php echo htmlspecialchars($tugas['deskripsi']); ?></td>
                            <td class="deadline <?php echo $tugas['status_tugas'] == 'terlambat' ? 'terlambat' : 'ontime'; ?>">
                                <?php echo date('d/m/Y H:i', strtotime($tugas['deadline'])); ?>
                            </td>
                            <td>
                                <?php 
                                switch ($tugas['status_tugas']) {
                                    case 'sudah_dikerjakan':
                                        echo '<span class="status-badge status-selesai">Sudah Dikerjakan</span>';
                                        break;
                                    case 'terlambat':
                                        echo '<span class="status-badge status-terlambat">Terlambat</span>';
                                        break;
                                    case 'belum_dikerjakan':
                                        echo '<span class="status-badge status-belum">Belum Dikerjakan</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($tugas['nilai']): ?>
                                    <strong style="color: #28a745;"><?php echo $tugas['nilai']; ?></strong>
                                <?php else: ?>
                                    <span style="color: #6c757d;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($tugas['status_tugas'] == 'sudah_dikerjakan'): ?>
                                    <span style="color: #28a745; font-weight: 600;">✓ Selesai</span>
                                <?php else: ?>
                                    <a href="kerjakan_tugas.php?id=<?php echo $tugas['id']; ?>" class="btn btn-warning">
                                        <?php echo $tugas['status_tugas'] == 'terlambat' ? 'Kerjakan (Terlambat)' : 'Kerjakan'; ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <h3>🎉 Tidak ada tugas yang tersedia!</h3>
                <p>Semua tugas sudah selesai atau belum ada tugas yang dibuat oleh guru.</p>
            </div>
        <?php endif; ?>
        
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