<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo 'Data pengumpulan tidak ditemukan.';
    exit;
}

$guru_id = $_SESSION['user_id'];
$pengumpulan_id = intval($_GET['id']);
$error = '';
$success = '';

// Ambil data pengumpulan dan pastikan milik guru
$q = mysqli_query($conn, "
    SELECT p.*, u.nama_lengkap, t.judul AS judul_tugas, m.nama_mapel
    FROM pengumpulan p
    JOIN tugas t ON p.tugas_id = t.id
    JOIN mata_pelajaran m ON t.mapel_id = m.id
    JOIN users u ON p.siswa_id = u.id
    WHERE p.id = $pengumpulan_id AND m.guru_id = $guru_id
");
$data = mysqli_fetch_assoc($q);
if (!$data) {
    echo 'Data pengumpulan tidak ditemukan atau Anda tidak punya akses.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = floatval($_POST['nilai']);
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    if ($nilai < 0 || $nilai > 100) {
        $error = 'Nilai harus antara 0-100.';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE pengumpulan SET nilai=?, komentar=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'dsi', $nilai, $komentar, $pengumpulan_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Nilai berhasil disimpan!';
        } else {
            $error = 'Gagal menyimpan nilai.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Nilai Tugas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 500px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="lihat_pengumpulan.php?tugas_id=<?php echo $data['tugas_id']; ?>">Kembali ke Pengumpulan</a>
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Beri Nilai Tugas</h2>
        <p><strong><?php echo htmlspecialchars($data['judul_tugas']); ?></strong> (<?php echo htmlspecialchars($data['nama_mapel']); ?>)</p>
        <p>Nama Siswa: <strong><?php echo htmlspecialchars($data['nama_lengkap']); ?></strong></p>
        <p>File: <?php if ($data['file_name']): ?><a href="uploads/<?php echo urlencode($data['file_name']); ?>" target="_blank">Download</a><?php else: echo '-'; endif; ?></p>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="nilai">Nilai (0-100)</label>
                <input type="number" name="nilai" id="nilai" min="0" max="100" step="0.01" required value="<?php echo htmlspecialchars($data['nilai'] ?? $_POST['nilai'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="komentar">Komentar Guru</label>
                <textarea name="komentar" id="komentar" rows="3"><?php echo htmlspecialchars($data['komentar'] ?? $_POST['komentar'] ?? ''); ?></textarea>
            </div>
            <button type="submit">Simpan Nilai</button>
        </form>
    </div>
</body>
</html> 