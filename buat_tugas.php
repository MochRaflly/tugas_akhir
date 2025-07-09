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
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $mapel_id = intval($_POST['mapel_id']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);

    // Validasi
    $cek_mapel = mysqli_query($conn, "SELECT id FROM mata_pelajaran WHERE id=$mapel_id AND guru_id=$guru_id");
    if (mysqli_num_rows($cek_mapel) === 0) {
        $error = 'Mata pelajaran tidak valid.';
    } elseif (!$judul || !$deadline) {
        $error = 'Judul dan deadline wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO tugas (judul, deskripsi, mapel_id, deadline) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssis', $judul, $deskripsi, $mapel_id, $deadline);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Tugas berhasil ditambahkan!';
        } else {
            $error = 'Gagal menyimpan ke database.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Tugas</title>
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
        input[type="text"], textarea, select, input[type="datetime-local"] { width: 100%; padding: 8px; box-sizing: border-box; }
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
        <h2>Buat Tugas Baru</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        <form method="post">
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
                <label for="judul">Judul Tugas</label>
                <input type="text" name="judul" id="judul" required value="<?php echo htmlspecialchars($_POST['judul'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"><?php echo htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="datetime-local" name="deadline" id="deadline" required value="<?php echo htmlspecialchars($_POST['deadline'] ?? ''); ?>">
            </div>
            <button type="submit">Buat Tugas</button>
        </form>
    </div>
</body>
</html> 