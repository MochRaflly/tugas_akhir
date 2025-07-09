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

// Ambil mapel yang diajar guru
$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE guru_id = $guru_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);
    $mapel_id = intval($_POST['mapel_id']);
    $file_name = '';

    // Validasi mapel milik guru
    $cek_mapel = mysqli_query($conn, "SELECT id FROM mata_pelajaran WHERE id=$mapel_id AND guru_id=$guru_id");
    if (mysqli_num_rows($cek_mapel) === 0) {
        $error = 'Mata pelajaran tidak valid.';
    } elseif (!$judul) {
        $error = 'Judul wajib diisi.';
    } else {
        // Proses file jika ada
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            $ori_name = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            $ext = strtolower(pathinfo($ori_name, PATHINFO_EXTENSION));
            $file_name = uniqid('materi_') . '.' . $ext;
            $upload_dir = 'uploads/';
            if (!in_array($ext, $allowed)) {
                $error = 'File harus berupa PDF atau gambar (jpg, jpeg, png).';
            } else {
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                if (!move_uploaded_file($tmp, $upload_dir . $file_name)) {
                    $error = 'Gagal upload file.';
                }
            }
        }
        if (!$error) {
            $stmt = mysqli_prepare($conn, "INSERT INTO materi (judul, konten, mapel_id, file_name) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssis', $judul, $konten, $mapel_id, $file_name);
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Materi berhasil ditambahkan!';
            } else {
                $error = 'Gagal menyimpan ke database.';
                if ($file_name && file_exists($upload_dir . $file_name)) unlink($upload_dir . $file_name);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Materi</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 600px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; box-sizing: border-box; }
        input[type="file"] { width: 100%; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="kelola_mapel.php">Kelola Mapel</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Tambah Materi</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="mapel_id">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" required>
                    <option value="">-- Pilih Mapel --</option>
                    <?php while($m = mysqli_fetch_assoc($mapel_result)): ?>
                        <option value="<?php echo $m['id']; ?>" <?php if(isset($_POST['mapel_id']) && $_POST['mapel_id']==$m['id']) echo 'selected'; ?>><?php echo htmlspecialchars($m['nama_mapel']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="judul">Judul Materi</label>
                <input type="text" name="judul" id="judul" required value="<?php echo htmlspecialchars($_POST['judul'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="konten">Konten</label>
                <textarea name="konten" id="konten" rows="5" required><?php echo htmlspecialchars($_POST['konten'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="file">File Materi (PDF/JPG/PNG, opsional)</label>
                <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <button type="submit">Tambah Materi</button>
        </form>
    </div>
</body>
</html> 