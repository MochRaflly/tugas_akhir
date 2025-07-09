<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['tugas_id'])) {
    echo 'Tugas tidak ditemukan.';
    exit;
}

$tugas_id = intval($_GET['tugas_id']);
$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Cek apakah sudah pernah upload
$cek = mysqli_query($conn, "SELECT id FROM pengumpulan WHERE tugas_id=$tugas_id AND siswa_id=$user_id");
if (mysqli_num_rows($cek) > 0) {
    $error = 'Anda sudah mengumpulkan tugas ini.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_name = uniqid('tugas_') . '.' . $file_ext;
        $upload_dir = 'uploads/';

        if (!in_array($file_ext, $allowed)) {
            $error = 'File harus berupa PDF atau gambar (jpg, jpeg, png).';
        } else {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                $tanggal_kirim = date('Y-m-d H:i:s');
                $stmt = mysqli_prepare($conn, "INSERT INTO pengumpulan (tugas_id, siswa_id, file_name, tanggal_kirim) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, 'iiss', $tugas_id, $user_id, $new_name, $tanggal_kirim);
                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Tugas berhasil diupload!';
                } else {
                    $error = 'Gagal menyimpan ke database.';
                    unlink($upload_dir . $new_name);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = 'Gagal mengupload file.';
            }
        }
    } else {
        $error = 'Pilih file untuk diupload.';
    }
}

// Ambil info tugas
$tugas = mysqli_query($conn, "SELECT t.judul, m.nama_mapel FROM tugas t JOIN mata_pelajaran m ON t.mapel_id = m.id WHERE t.id = $tugas_id");
$tugas_data = mysqli_fetch_assoc($tugas);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Tugas</title>
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
        input[type="file"] { width: 100%; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="daftar_tugas.php">Daftar Tugas</a>
            <a href="dashboard_siswa.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Upload Tugas</h2>
        <?php if ($tugas_data): ?>
            <p><strong><?php echo htmlspecialchars($tugas_data['judul']); ?></strong> (<?php echo htmlspecialchars($tugas_data['nama_mapel']); ?>)</p>
        <?php endif; ?>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php else: ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">File Tugas (PDF/JPG/PNG):</label>
                <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>
            <button type="submit">Upload</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html> 