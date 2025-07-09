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
    <title>Kelola Materi</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 800px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="tambah_materi.php">Tambah Materi</a>
            <a href="kelola_mapel.php">Kelola Mapel</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Kelola Materi</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        <form method="get" style="margin-bottom:20px;">
            <label for="mapel_id">Pilih Mata Pelajaran:</label>
            <select name="mapel_id" id="mapel_id" required onchange="this.form.submit()">
                <option value="">-- Pilih Mapel --</option>
                <?php while($m = mysqli_fetch_assoc($mapel_result)): ?>
                    <option value="<?php echo $m['id']; ?>" <?php if($mapel_id==$m['id']) echo 'selected'; ?>><?php echo htmlspecialchars($m['nama_mapel']); ?></option>
                <?php endwhile; ?>
            </select>
        </form>
        <?php if ($mapel_data): ?>
            <h3>Materi untuk Mapel: <?php echo htmlspecialchars($mapel_data['nama_mapel']); ?></h3>
            <table>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
                <?php if ($materi_result && mysqli_num_rows($materi_result) > 0): $no=1; while($materi = mysqli_fetch_assoc($materi_result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($materi['judul']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($materi['konten'])); ?></td>
                    <td>
                        <?php if ($materi['file_name']): ?>
                            <a href="uploads/<?php echo urlencode($materi['file_name']); ?>" target="_blank">Download</a>
                        <?php else: echo '-'; endif; ?>
                    </td>
                    <td>
                        <a href="edit_materi.php?id=<?php echo $materi['id']; ?>">Edit</a> |
                        <a href="kelola_materi.php?mapel_id=<?php echo $mapel_id; ?>&hapus=<?php echo $materi['id']; ?>" onclick="return confirm('Yakin hapus materi ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5">Belum ada materi.</td></tr>
                <?php endif; ?>
            </table>
        <?php elseif($mapel_id): ?>
            <p>Mata pelajaran tidak ditemukan atau Anda tidak memiliki akses.</p>
        <?php endif; ?>
    </div>
</body>
</html> 