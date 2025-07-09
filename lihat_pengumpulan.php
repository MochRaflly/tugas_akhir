<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['tugas_id'])) {
    echo 'Tugas tidak ditemukan.';
    exit;
}

$guru_id = $_SESSION['user_id'];
$tugas_id = intval($_GET['tugas_id']);

// Cek tugas milik guru
$tugas = mysqli_query($conn, "SELECT t.*, m.nama_mapel FROM tugas t JOIN mata_pelajaran m ON t.mapel_id = m.id WHERE t.id=$tugas_id AND m.guru_id=$guru_id");
$tugas_data = mysqli_fetch_assoc($tugas);
if (!$tugas_data) {
    echo 'Tugas tidak ditemukan atau Anda tidak punya akses.';
    exit;
}

// Ambil pengumpulan siswa
$pengumpulan_result = mysqli_query($conn, "
    SELECT p.*, u.nama_lengkap
    FROM pengumpulan p
    JOIN users u ON p.siswa_id = u.id
    WHERE p.tugas_id = $tugas_id
    ORDER BY p.tanggal_kirim ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengumpulan Tugas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 800px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="kelola_tugas.php">Kembali ke Kelola Tugas</a>
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Pengumpulan Tugas: <?php echo htmlspecialchars($tugas_data['judul']); ?> (<?php echo htmlspecialchars($tugas_data['nama_mapel']); ?>)</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>File</th>
                <th>Tanggal Kirim</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($row = mysqli_fetch_assoc($pengumpulan_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                <td>
                    <?php if ($row['file_name']): ?>
                        <a href="uploads/<?php echo urlencode($row['file_name']); ?>" target="_blank">Download</a>
                    <?php else: echo '-'; endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['tanggal_kirim']); ?></td>
                <td><?php echo htmlspecialchars($row['nilai']); ?></td>
                <td><a href="beri_nilai.php?id=<?php echo $row['id']; ?>">Beri Nilai</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 