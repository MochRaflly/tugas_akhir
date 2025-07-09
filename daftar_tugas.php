<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua tugas dan status pengumpulan siswa
$tugas_result = mysqli_query($conn, "
    SELECT t.*, m.nama_mapel,
        (SELECT id FROM pengumpulan p WHERE p.tugas_id = t.id AND p.siswa_id = $user_id LIMIT 1) AS sudah_kumpul
    FROM tugas t
    JOIN mata_pelajaran m ON t.mapel_id = m.id
    ORDER BY t.deadline DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas</title>
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
            <a href="daftar_mapel.php">Daftar Mapel</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Daftar Tugas</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Judul Tugas</th>
                <th>Mata Pelajaran</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($tugas = mysqli_fetch_assoc($tugas_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($tugas['judul']); ?></td>
                <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($tugas['deadline']); ?></td>
                <td>
                    <?php if ($tugas['sudah_kumpul']) {
                        echo '<span style=\'color:green\'>Sudah dikumpulkan</span>';
                    } else {
                        echo '<span style=\'color:red\'>Belum dikumpulkan</span>';
                    } ?>
                </td>
                <td>
                    <?php if (!$tugas['sudah_kumpul']) { ?>
                        <a href="upload_tugas.php?tugas_id=<?php echo $tugas['id']; ?>">Upload</a>
                    <?php } else { echo '-'; } ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 