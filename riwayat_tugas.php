<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua tugas yang pernah dikumpulkan siswa
$result = mysqli_query($conn, "
    SELECT p.*, t.judul AS judul_tugas, m.nama_mapel, t.deadline, u.nama_lengkap AS guru_nama
    FROM pengumpulan p
    JOIN tugas t ON p.tugas_id = t.id
    JOIN mata_pelajaran m ON t.mapel_id = m.id
    JOIN users u ON m.guru_id = u.id
    WHERE p.siswa_id = $user_id
    ORDER BY p.tanggal_kirim DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Tugas Saya</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 900px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_siswa.php">Dashboard</a>
            <a href="daftar_tugas.php">Daftar Tugas</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Riwayat Tugas yang Pernah Dikumpulkan</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Judul Tugas</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Deadline</th>
                <th>Tanggal Kirim</th>
                <th>Nilai</th>
                <th>Komentar Guru</th>
                <th>File</th>
            </tr>
            <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['judul_tugas']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($row['guru_nama']); ?></td>
                <td><?php echo htmlspecialchars($row['deadline']); ?></td>
                <td><?php echo htmlspecialchars($row['tanggal_kirim']); ?></td>
                <td><?php echo htmlspecialchars($row['nilai']); ?></td>
                <td><?php echo htmlspecialchars($row['komentar']); ?></td>
                <td>
                    <?php if ($row['file_name']): ?>
                        <a href="uploads/<?php echo urlencode($row['file_name']); ?>" target="_blank">Download</a>
                    <?php else: echo '-'; endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 