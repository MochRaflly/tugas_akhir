<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit();
}

$mapel_id = $_GET['mapel_id'] ?? 0;

// Ambil daftar materi
if ($mapel_id) {
    $sql = "SELECT m.*, mp.nama_mapel as nama_mapel 
            FROM materi m 
            JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
            WHERE m.mapel_id = ? 
            ORDER BY m.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mapel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Ambil nama mata pelajaran
    $sql_mapel = "SELECT nama_mapel FROM mata_pelajaran WHERE id = ?";
    $stmt_mapel = $conn->prepare($sql_mapel);
    $stmt_mapel->bind_param("i", $mapel_id);
    $stmt_mapel->execute();
    $mapel_result = $stmt_mapel->get_result();
    $mapel = $mapel_result->fetch_assoc();
} else {
    $sql = "SELECT m.*, mp.nama_mapel as nama_mapel 
            FROM materi m 
            JOIN mata_pelajaran mp ON m.mapel_id = mp.id 
            ORDER BY m.id DESC";
    $result = $conn->query($sql);
    $mapel = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Materi - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📖 Daftar Materi Pembelajaran</h2>
        
        <?php if ($mapel): ?>
            <p style="color: #666; margin-bottom: 30px;">Mata Pelajaran: <strong><?php echo htmlspecialchars($mapel['nama_mapel']); ?></strong></p>
        <?php endif; ?>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Judul Materi</th>
                        <?php if (!$mapel): ?>
                            <th>Mata Pelajaran</th>
                        <?php endif; ?>
                        <th>Deskripsi</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($materi = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($materi['judul']); ?></strong></td>
                            <?php if (!$mapel): ?>
                                <td><?php echo htmlspecialchars($materi['nama_mapel']); ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($materi['deskripsi']); ?></td>
                            <td>
                                <?php if ($materi['file_path']): ?>
                                    <a href="<?php echo $materi['file_path']; ?>" class="file-link" target="_blank">📄 Download</a>
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
                <h3>📚 Belum ada materi</h3>
                <p><?php echo $mapel ? 'Materi untuk mata pelajaran ini belum diupload oleh guru.' : 'Belum ada materi yang diupload oleh guru.'; ?></p>
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