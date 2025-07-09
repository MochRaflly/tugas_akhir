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
    <title>Kelola Mata Pelajaran</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 700px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 16px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Kelola Mata Pelajaran</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

        <h3><?php echo $edit_data ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran'; ?></h3>
        <form method="post">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="nama_mapel">Nama Mapel</label>
                <input type="text" id="nama_mapel" name="nama_mapel" required value="<?php echo htmlspecialchars($edit_data['nama_mapel'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="2"><?php echo htmlspecialchars($edit_data['deskripsi'] ?? ''); ?></textarea>
            </div>
            <button type="submit" name="<?php echo $edit_data ? 'edit' : 'tambah'; ?>"><?php echo $edit_data ? 'Update' : 'Tambah'; ?></button>
            <?php if ($edit_data): ?>
                <a href="kelola_mapel.php" style="margin-left:10px;">Batal</a>
            <?php endif; ?>
        </form>

        <h3>Daftar Mata Pelajaran Anda</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Mapel</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($mapel = mysqli_fetch_assoc($mapel_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($mapel['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($mapel['deskripsi']); ?></td>
                <td>
                    <a href="kelola_mapel.php?edit=<?php echo $mapel['id']; ?>">Edit</a> |
                    <a href="kelola_mapel.php?hapus=<?php echo $mapel['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 