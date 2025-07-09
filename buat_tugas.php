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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📝 Buat Tugas Baru</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form Buat Tugas -->
        <div class="panel">
            <h3>➕ Buat Tugas Baru untuk Siswa</h3>
            <form method="post" class="form">
                <div class="form-group">
                    <label for="mapel_id">Mata Pelajaran</label>
                    <select name="mapel_id" id="mapel_id" required class="form-control">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php while($m = mysqli_fetch_assoc($mapel_result)): ?>
                            <option value="<?php echo $m['id']; ?>" 
                                    <?php if(isset($_POST['mapel_id']) && $_POST['mapel_id']==$m['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($m['nama_mapel']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="judul">Judul Tugas</label>
                    <input type="text" name="judul" id="judul" required 
                           value="<?php echo htmlspecialchars($_POST['judul'] ?? ''); ?>"
                           placeholder="Contoh: Tugas Matematika Bab 1, Essay Bahasa Indonesia, dll."
                           class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Tugas</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              placeholder="Jelaskan detail tugas yang harus dikerjakan siswa..."
                              class="form-control"><?php echo htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="datetime-local" name="deadline" id="deadline" required 
                           value="<?php echo htmlspecialchars($_POST['deadline'] ?? ''); ?>"
                           class="form-control">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        ⏰ Pilih tanggal dan waktu batas akhir pengumpulan tugas
                    </small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        📝 Buat Tugas
                    </button>
                    <a href="dashboard_guru.php" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>

        <!-- Tips -->
        <div class="panel">
            <h3>💡 Tips Membuat Tugas yang Baik</h3>
            <ul style="color: #666; line-height: 1.6;">
                <li>Berikan judul yang jelas dan spesifik</li>
                <li>Jelaskan deskripsi tugas secara detail agar siswa memahami apa yang harus dikerjakan</li>
                <li>Atur deadline yang realistis dan memberikan waktu cukup untuk siswa</li>
                <li>Pastikan mata pelajaran yang dipilih sudah benar</li>
            </ul>
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