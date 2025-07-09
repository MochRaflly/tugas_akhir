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

// Ambil tugas yang dibuat guru (berdasarkan mapel yang diajar)
$tugas_result = mysqli_query($conn, "
    SELECT t.*, m.nama_mapel
    FROM tugas t
    JOIN mata_pelajaran m ON t.mapel_id = m.id
    WHERE m.guru_id = $guru_id
    ORDER BY t.deadline DESC
");

// Hapus tugas
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Hapus pengumpulan terkait
    mysqli_query($conn, "DELETE FROM pengumpulan WHERE tugas_id=$id");
    // Hapus tugas
    $q = mysqli_query($conn, "DELETE FROM tugas WHERE id=$id");
    if ($q) $success = 'Tugas berhasil dihapus.';
    else $error = 'Gagal menghapus tugas.';
    // Refresh data
    $tugas_result = mysqli_query($conn, "
        SELECT t.*, m.nama_mapel
        FROM tugas t
        JOIN mata_pelajaran m ON t.mapel_id = m.id
        WHERE m.guru_id = $guru_id
        ORDER BY t.deadline DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Tugas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 900px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="buat_tugas.php">Buat Tugas</a>
            <a href="kelola_mapel.php">Kelola Mapel</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Kelola Tugas</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        <table>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Mata Pelajaran</th>
                <th>Deadline</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($tugas = mysqli_fetch_assoc($tugas_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($tugas['judul']); ?></td>
                <td><?php echo htmlspecialchars($tugas['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($tugas['deadline']); ?></td>
                <td>
                    <a href="edit_tugas.php?id=<?php echo $tugas['id']; ?>">Edit</a> |
                    <a href="kelola_tugas.php?hapus=<?php echo $tugas['id']; ?>" onclick="return confirm('Yakin hapus tugas ini?')">Hapus</a> |
                    <a href="lihat_pengumpulan.php?tugas_id=<?php echo $tugas['id']; ?>">Lihat Pengumpulan</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 