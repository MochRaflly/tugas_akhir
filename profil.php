<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$error = '';
$success = '';

// Ambil data user
$user = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$data = mysqli_fetch_assoc($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $password = $_POST['password'];
    $update_query = '';
    if (!$nama_lengkap) {
        $error = 'Nama lengkap wajib diisi.';
    } else {
        if ($password) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET nama_lengkap='$nama_lengkap', password='$password_hash' WHERE id=$user_id";
        } else {
            $update_query = "UPDATE users SET nama_lengkap='$nama_lengkap' WHERE id=$user_id";
        }
        if (mysqli_query($conn, $update_query)) {
            $success = 'Profil berhasil diupdate!';
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            // Refresh data
            $user = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
            $data = mysqli_fetch_assoc($user);
        } else {
            $error = 'Gagal mengupdate profil.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
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
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <?php if ($role === 'guru'): ?>
                <a href="dashboard_guru.php">Dashboard</a>
            <?php else: ?>
                <a href="dashboard_siswa.php">Dashboard</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Edit Profil</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" required value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password Baru (kosongkan jika tidak ingin ganti)</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html> 