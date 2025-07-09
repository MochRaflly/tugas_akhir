<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Ambil data profil dengan error handling
try {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} catch (Exception $e) {
    header("Location: login.php");
    exit();
}

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'] ?? '';
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    
    $error = '';
    $success = '';
    
    // Validasi input
    if (empty($nama)) {
        $error = "Nama tidak boleh kosong!";
    } elseif ($password_baru && $password_baru != $konfirmasi_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif ($password_baru && strlen($password_baru) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password_baru && !password_verify($password_lama, $user['password'])) {
        $error = "Password lama salah!";
    } else {
        // Update data
        if ($password_baru) {
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET nama = ?, email = ?, password = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sssi", $nama, $email, $hashed_password, $user_id);
        } else {
            $sql_update = "UPDATE users SET nama = ?, email = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssi", $nama, $email, $user_id);
        }
        
        if ($stmt_update->execute()) {
            $success = "Profil berhasil diperbarui!";
            $_SESSION['nama'] = $nama;
            
            // Refresh data user
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Gagal memperbarui profil!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>👤 Profil Saya</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <!-- Informasi Profil -->
        <div class="panel">
            <h3>📋 Informasi Profil</h3>
            <table>
                <tr>
                    <td width="150"><strong>Username:</strong></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Lengkap:</strong></td>
                    <td><?php echo htmlspecialchars($user['nama'] ?? $user['nama_lengkap'] ?? 'Tidak ada'); ?></td>
                </tr>
                <tr>
                    <td><strong>Role:</strong></td>
                    <td>
                        <span class="status-badge <?php echo $user['role'] == 'guru' ? 'status-selesai' : 'status-info'; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                </tr>
                <?php if ($user['role'] == 'siswa' && isset($user['kelas']) && $user['kelas']): ?>
                <tr>
                    <td><strong>Kelas:</strong></td>
                    <td><?php echo htmlspecialchars($user['kelas']); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td><strong>Bergabung Sejak:</strong></td>
                    <td><?php echo isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : '-'; ?></td>
                </tr>
            </table>
        </div>
        
        <!-- Form Edit Profil -->
        <div class="panel">
            <h3>✏️ Edit Profil</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama'] ?? $user['nama_lengkap'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email (Opsional):</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                </div>
                
                <hr style="margin: 30px 0; border: 1px solid #e9ecef;">
                
                <h4>🔐 Ubah Password (Opsional)</h4>
                <div class="form-group">
                    <label for="password_lama">Password Lama:</label>
                    <input type="password" id="password_lama" name="password_lama">
                </div>
                
                <div class="form-group">
                    <label for="password_baru">Password Baru:</label>
                    <input type="password" id="password_baru" name="password_baru" minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password Baru:</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" minlength="6">
                </div>
                
                <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
            </form>
        </div>
        
        <!-- Navigation -->
        <div class="nav">
            <?php if ($role == 'guru'): ?>
                <a href="dashboard_guru.php">🏠 Dashboard</a>
                <a href="kelola_mapel.php">📚 Mata Pelajaran</a>
                <a href="kelola_materi.php">📖 Materi</a>
                <a href="buat_tugas.php">📝 Tugas</a>
                <a href="laporan_nilai.php">📊 Nilai</a>
            <?php else: ?>
                <a href="dashboard_siswa.php">🏠 Dashboard</a>
                <a href="daftar_tugas.php">📋 Tugas</a>
                <a href="daftar_mapel.php">📚 Mata Pelajaran</a>
                <a href="daftar_materi.php">📖 Materi</a>
                <a href="nilai_saya.php">📊 Nilai</a>
            <?php endif; ?>
            <a href="profil.php">👤 Profil</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>
</body>
</html> 