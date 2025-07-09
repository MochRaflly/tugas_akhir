<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: login.php');
    exit;
}

$guru_id = $_SESSION['user_id'];
$nama_lengkap = $_SESSION['nama_lengkap'];

// Daftar mata pelajaran yang diajar guru
$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE guru_id = $guru_id");

// Tugas yang perlu dinilai (ada pengumpulan yang belum dinilai)
$tugas_nilai_result = mysqli_query($conn, "
    SELECT p.*, t.judul AS judul_tugas, m.nama_mapel, u.nama_lengkap AS nama_siswa
    FROM pengumpulan p
    JOIN tugas t ON p.tugas_id = t.id
    JOIN mata_pelajaran m ON t.mapel_id = m.id
    JOIN users u ON p.siswa_id = u.id
    WHERE m.guru_id = $guru_id AND (p.nilai IS NULL OR p.nilai = '')
    ORDER BY p.tanggal_kirim ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 900px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .section-title { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Selamat datang, <?php echo htmlspecialchars($nama_lengkap); ?>!</h2>

        <h3 class="section-title">Mata Pelajaran yang Diajar</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Mapel</th>
                <th>Deskripsi</th>
            </tr>
            <?php $no=1; while($mapel = mysqli_fetch_assoc($mapel_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($mapel['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($mapel['deskripsi']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3 class="section-title">Tugas yang Perlu Dinilai</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Judul Tugas</th>
                <th>Mata Pelajaran</th>
                <th>Nama Siswa</th>
                <th>Tanggal Kirim</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($nilai = mysqli_fetch_assoc($tugas_nilai_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($nilai['judul_tugas']); ?></td>
                <td><?php echo htmlspecialchars($nilai['nama_mapel']); ?></td>
                <td><?php echo htmlspecialchars($nilai['nama_siswa']); ?></td>
                <td><?php echo htmlspecialchars($nilai['tanggal_kirim']); ?></td>
                <td>
                    <?php if ($nilai['file_name']) { ?>
                        <a href="uploads/<?php echo urlencode($nilai['file_name']); ?>" target="_blank">Lihat File</a>
                    <?php } else { echo '-'; } ?>
                </td>
                <td><a href="nilai_tugas.php?id=<?php echo $nilai['id']; ?>">Nilai</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 