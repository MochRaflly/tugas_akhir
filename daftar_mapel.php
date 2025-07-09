<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit();
}

// Ambil daftar mata pelajaran dengan error handling
try {
    $sql = "SELECT mp.*, u.nama as nama_guru 
            FROM mata_pelajaran mp 
            LEFT JOIN users u ON mp.guru_id = u.id 
            ORDER BY mp.nama ASC";
    $result = $conn->query($sql);
} catch (Exception $e) {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mata Pelajaran - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📚 Daftar Mata Pelajaran</h2>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Deskripsi</th>
                        <th>Guru Pengampu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($mapel = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($mapel['nama']); ?></strong></td>
                            <td><?php echo htmlspecialchars($mapel['deskripsi']); ?></td>
                            <td><?php echo htmlspecialchars($mapel['nama_guru'] ?? 'Belum ditentukan'); ?></td>
                            <td>
                                <a href="daftar_materi.php?mapel_id=<?php echo $mapel['id']; ?>" class="btn btn-info">Lihat Materi</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <h3>📚 Belum ada mata pelajaran</h3>
                <p>Mata pelajaran akan ditambahkan oleh guru.</p>
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