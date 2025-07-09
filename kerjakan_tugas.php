<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$tugas_id = $_GET['id'] ?? 0;

// Ambil detail tugas
$sql_tugas = "SELECT t.*, mp.nama as nama_mapel FROM tugas t 
              JOIN mata_pelajaran mp ON t.mapel_id = mp.id 
              WHERE t.id = ?";
$stmt_tugas = $conn->prepare($sql_tugas);
$stmt_tugas->bind_param("i", $tugas_id);
$stmt_tugas->execute();
$result_tugas = $stmt_tugas->get_result();

if ($result_tugas->num_rows == 0) {
    header("Location: daftar_tugas.php");
    exit();
}

$tugas = $result_tugas->fetch_assoc();

// Cek apakah sudah pernah dikumpulkan
$sql_check = "SELECT * FROM pengumpulan WHERE tugas_id = ? AND siswa_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $tugas_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$sudah_dikumpulkan = $result_check->num_rows > 0;

if ($sudah_dikumpulkan) {
    $pengumpulan = $result_check->fetch_assoc();
}

// Proses submit tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$sudah_dikumpulkan) {
    $komentar = $_POST['komentar'] ?? '';
    $file_path = '';
    
    // Upload file jika ada
    if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . $_FILES['file_tugas']['name'];
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $file_path)) {
            // File berhasil diupload
        } else {
            $error = "Gagal mengupload file!";
        }
    }
    
    if (!isset($error)) {
        $sql_insert = "INSERT INTO pengumpulan (tugas_id, siswa_id, file_path, komentar) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iiss", $tugas_id, $user_id, $file_path, $komentar);
        
        if ($stmt_insert->execute()) {
            $success = "Tugas berhasil dikumpulkan!";
            $sudah_dikumpulkan = true;
        } else {
            $error = "Gagal mengumpulkan tugas!";
        }
    }
}

// Cek status deadline
$deadline = new DateTime($tugas['deadline']);
$now = new DateTime();
$terlambat = $now > $deadline;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjakan Tugas - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📝 Kerjakan Tugas</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <!-- Detail Tugas -->
        <div class="panel">
            <h3>📋 Detail Tugas</h3>
            <table>
                <tr>
                    <td width="150"><strong>Judul:</strong></td>
                    <td><?php echo htmlspecialchars($tugas['judul']); ?></td>
                </tr>
                <tr>
                    <td><strong>Mata Pelajaran:</strong></td>
                    <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                </tr>
                <tr>
                    <td><strong>Deskripsi:</strong></td>
                    <td><?php echo nl2br(htmlspecialchars($tugas['deskripsi'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Deadline:</strong></td>
                    <td class="deadline <?php echo $terlambat ? 'terlambat' : 'ontime'; ?>">
                        <?php echo date('d/m/Y H:i', strtotime($tugas['deadline'])); ?>
                        <?php if ($terlambat): ?>
                            <span class="status-badge status-terlambat">Terlambat</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php if ($sudah_dikumpulkan): ?>
            <!-- Status Pengumpulan -->
            <div class="panel">
                <h3>✅ Status Pengumpulan</h3>
                <div class="success">
                    <h4>🎉 Tugas sudah dikumpulkan!</h4>
                    <p><strong>Tanggal Submit:</strong> <?php echo date('d/m/Y H:i', strtotime($pengumpulan['created_at'])); ?></p>
                    <?php if ($pengumpulan['file_path']): ?>
                        <p><strong>File:</strong> <a href="<?php echo $pengumpulan['file_path']; ?>" class="file-link" target="_blank">Download File</a></p>
                    <?php endif; ?>
                    <?php if ($pengumpulan['komentar']): ?>
                        <p><strong>Komentar:</strong> <?php echo nl2br(htmlspecialchars($pengumpulan['komentar'])); ?></p>
                    <?php endif; ?>
                    <?php if ($pengumpulan['nilai']): ?>
                        <p><strong>Nilai:</strong> <span style="color: #28a745; font-weight: bold; font-size: 1.2em;"><?php echo $pengumpulan['nilai']; ?></span></p>
                    <?php else: ?>
                        <p><strong>Status:</strong> <span class="status-badge status-belum">Belum Dinilai</span></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Form Pengumpulan -->
            <div class="panel">
                <h3>📤 Kumpulkan Tugas</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file_tugas">File Tugas (PDF, DOC, DOCX, ZIP):</label>
                        <input type="file" id="file_tugas" name="file_tugas" accept=".pdf,.doc,.docx,.zip,.rar">
                        <small style="color: #6c757d;">Maksimal 10MB</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="komentar">Komentar (Opsional):</label>
                        <textarea id="komentar" name="komentar" rows="4" placeholder="Tambahkan komentar atau catatan untuk guru..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <?php echo $terlambat ? 'Kumpulkan (Terlambat)' : 'Kumpulkan Tugas'; ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div class="nav">
            <a href="dashboard_siswa.php">🏠 Dashboard</a>
            <a href="daftar_tugas.php">📋 Tugas</a>
            <a href="daftar_mapel.php">📚 Mata Pelajaran</a>
            <a href="daftar_materi.php">📖 Materi</a>
            <a href="nilai_saya.php">📊 Nilai</a>
            <a href="profil.php">👤 Profil</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>
</body>
</html> 