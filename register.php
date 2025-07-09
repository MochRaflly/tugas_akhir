<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $kelas = $_POST['kelas'] ?? '';
    
    // Validasi input
    if (empty($username) || empty($password) || empty($nama) || empty($role)) {
        $error = "Semua field harus diisi!";
    } else {
        // Cek apakah username sudah ada
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user baru
            $insert_sql = "INSERT INTO users (username, password, nama, role, kelas) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssss", $username, $hashed_password, $nama, $role, $kelas);
            
            if ($insert_stmt->execute()) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Gagal mendaftar: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>📝 Register</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>
            
            <div class="form-group" id="kelas-group" style="display: none;">
                <label for="kelas">Kelas:</label>
                <select id="kelas" name="kelas">
                    <option value="">Pilih Kelas</option>
                    <option value="X">Kelas X</option>
                    <option value="XI">Kelas XI</option>
                    <option value="XII">Kelas XII</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p>Sudah punya akun? <a href="login.php" class="btn btn-info">Login</a></p>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            const kelasGroup = document.getElementById('kelas-group');
            if (this.value === 'siswa') {
                kelasGroup.style.display = 'block';
                document.getElementById('kelas').required = true;
            } else {
                kelasGroup.style.display = 'none';
                document.getElementById('kelas').required = false;
            }
        });
    </script>
</body>
</html> 