<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mata Pelajaran</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 700px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
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
            <a href="logout.php">Logout</a>
        </div>
        <h2>Daftar Mata Pelajaran</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Mapel</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($mapel = mysqli_fetch_assoc($mapel_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($mapel['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($mapel['deskripsi']); ?></td>
                <td><a href="materi.php?mapel_id=<?php echo $mapel['id']; ?>">Lihat Materi</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 