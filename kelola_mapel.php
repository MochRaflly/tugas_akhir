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

// Tambah mapel
if (isset($_POST['tambah'])) {
    $nama_mapel = mysqli_real_escape_string($conn, $_POST['nama_mapel']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    if ($nama_mapel) {
        $q = mysqli_query($conn, "INSERT INTO mata_pelajaran (nama_mapel, deskripsi, guru_id) VALUES ('$nama_mapel', '$deskripsi', $guru_id)");
        if ($q) $success = 'Mata pelajaran berhasil ditambah.';
        else $error = 'Gagal menambah mata pelajaran.';
    } else {
        $error = 'Nama mapel wajib diisi.';
    }
}

// Edit mapel
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $nama_mapel = mysqli_real_escape_string($conn, $_POST['nama_mapel']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    if ($nama_mapel) {
        $q = mysqli_query($conn, "UPDATE mata_pelajaran SET nama_mapel='$nama_mapel', deskripsi='$deskripsi' WHERE id=$id AND guru_id=$guru_id");
        if ($q) $success = 'Mata pelajaran berhasil diupdate.';
        else $error = 'Gagal mengupdate mata pelajaran.';
    } else {
        $error = 'Nama mapel wajib diisi.';
    }
}

// Hapus mapel
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $q = mysqli_query($conn, "DELETE FROM mata_pelajaran WHERE id=$id AND guru_id=$guru_id");
    if ($q) $success = 'Mata pelajaran berhasil dihapus.';
    else $error = 'Gagal menghapus mata pelajaran.';
}

// Ambil data mapel milik guru
$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE guru_id = $guru_id ORDER BY id DESC");

// Untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE id=$id AND guru_id=$guru_id");
    $edit_data = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mata Pelajaran - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📚 Kelola Mata Pelajaran</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form Tambah/Edit -->
        <div class="panel">
            <h3><?php echo $edit_data ? '✏️ Edit Mata Pelajaran' : '➕ Tambah Mata Pelajaran Baru'; ?></h3>
            <form method="post" class="form">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="nama_mapel">Nama Mata Pelajaran</label>
                    <input type="text" id="nama_mapel" name="nama_mapel" required 
                           value="<?php echo htmlspecialchars($edit_data['nama_mapel'] ?? ''); ?>"
                           placeholder="Contoh: Matematika, Bahasa Indonesia, dll.">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" 
                              placeholder="Deskripsi singkat tentang mata pelajaran ini..."><?php echo htmlspecialchars($edit_data['deskripsi'] ?? ''); ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" name="<?php echo $edit_data ? 'edit' : 'tambah'; ?>" class="btn btn-primary">
                        <?php echo $edit_data ? '💾 Update Mata Pelajaran' : '➕ Tambah Mata Pelajaran'; ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="kelola_mapel.php" class="btn btn-secondary">❌ Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Daftar Mata Pelajaran -->
        <div class="panel">
            <h3>📋 Daftar Mata Pelajaran Anda</h3>
            <?php if (mysqli_num_rows($mapel_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($mapel = mysqli_fetch_assoc($mapel_result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($mapel['nama_mapel']); ?></strong></td>
                            <td><?php echo htmlspecialchars($mapel['deskripsi'] ?: '-'); ?></td>
                            <td>
                                <a href="kelola_mapel.php?edit=<?php echo $mapel['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                                <a href="kelola_mapel.php?hapus=<?php echo $mapel['id']; ?>" 
                                   onclick="return confirm('⚠️ Yakin ingin menghapus mata pelajaran ini?')" 
                                   class="btn btn-danger btn-sm">🗑️ Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">
                    <h3>📚 Belum ada mata pelajaran</h3>
                    <p>Anda belum membuat mata pelajaran. Silakan tambahkan mata pelajaran baru di atas.</p>
                </div>
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