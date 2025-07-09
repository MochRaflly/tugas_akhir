<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: login.php');
    exit;
}

$guru_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Ambil mapel milik guru
$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE guru_id = $guru_id");

// Pilih mapel
$mapel_id = isset($_GET['mapel_id']) ? intval($_GET['mapel_id']) : 0;
$materi_result = null;
$mapel_data = null;
if ($mapel_id) {
    $mapel_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE id=$mapel_id AND guru_id=$guru_id"));
    if ($mapel_data) {
        $materi_result = mysqli_query($conn, "SELECT * FROM materi WHERE mapel_id = $mapel_id ORDER BY id DESC");
    } else {
        $error = 'Mata pelajaran tidak valid.';
    }
}

// Hapus materi
if (isset($_GET['hapus']) && $mapel_id && $mapel_data) {
    $id = intval($_GET['hapus']);
    $materi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM materi WHERE id=$id AND mapel_id=$mapel_id"));
    if ($materi) {
        $q = mysqli_query($conn, "DELETE FROM materi WHERE id=$id");
        if ($q) {
            if ($materi['file_name'] && file_exists('uploads/' . $materi['file_name'])) {
                unlink('uploads/' . $materi['file_name']);
            }
            $success = 'Materi berhasil dihapus.';
            // Refresh data
            $materi_result = mysqli_query($conn, "SELECT * FROM materi WHERE mapel_id = $mapel_id ORDER BY id DESC");
        } else {
            $error = 'Gagal menghapus materi.';
        }
    } else {
        $error = 'Materi tidak ditemukan.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📖 Kelola Materi Pembelajaran</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
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

        <?php if ($mapel_data): ?>
            <!-- Daftar Materi -->
            <div class="panel">
                <h3>📋 Materi untuk: <strong><?php echo htmlspecialchars($mapel_data['nama_mapel']); ?></strong></h3>
                
                <div class="quick-actions" style="margin-bottom: 20px;">
                    <a href="tambah_materi.php?mapel_id=<?php echo $mapel_id; ?>" class="btn btn-primary">
                        ➕ Tambah Materi Baru
                    </a>
                </div>

                <?php if ($materi_result && mysqli_num_rows($materi_result) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Materi</th>
                                <th>Deskripsi</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; while($materi = mysqli_fetch_assoc($materi_result)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo htmlspecialchars($materi['judul']); ?></strong></td>
                                <td><?php echo htmlspecialchars($materi['konten'] ?: '-'); ?></td>
                                <td>
                                    <?php if ($materi['file_name']): ?>
                                        <a href="uploads/<?php echo urlencode($materi['file_name']); ?>" 
                                           target="_blank" class="file-link">📄 Download</a>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_materi.php?id=<?php echo $materi['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                                    <a href="kelola_materi.php?mapel_id=<?php echo $mapel_id; ?>&hapus=<?php echo $materi['id']; ?>" 
                                       onclick="return confirm('⚠️ Yakin ingin menghapus materi ini?')" 
                                       class="btn btn-danger btn-sm">🗑️ Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        <h3>📚 Belum ada materi</h3>
                        <p>Belum ada materi untuk mata pelajaran ini. Silakan tambahkan materi baru.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif($mapel_id): ?>
            <div class="alert alert-error">
                ❌ Mata pelajaran tidak ditemukan atau Anda tidak memiliki akses.
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