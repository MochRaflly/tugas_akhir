<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['mapel_id'])) {
    echo 'Mata pelajaran tidak ditemukan.';
    exit;
}

$mapel_id = intval($_GET['mapel_id']);
$mapel_query = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE id = $mapel_id");
$mapel = mysqli_fetch_assoc($mapel_query);
if (!$mapel) {
    echo 'Mata pelajaran tidak ditemukan.';
    exit;
}

$materi_result = mysqli_query($conn, "SELECT * FROM materi WHERE mapel_id = $mapel_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Materi: <?php echo htmlspecialchars($mapel['nama_mapel']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; max-width: 700px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .materi { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .judul { font-size: 1.2em; font-weight: bold; }
        .konten { margin: 10px 0; }
        .file-link { margin-top: 5px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="daftar_mapel.php">Daftar Mapel</a>
            <a href="dashboard_siswa.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Materi: <?php echo htmlspecialchars($mapel['nama_mapel']); ?></h2>
        <?php if (mysqli_num_rows($materi_result) === 0): ?>
            <p>Belum ada materi untuk mata pelajaran ini.</p>
        <?php else: ?>
            <?php while($materi = mysqli_fetch_assoc($materi_result)): ?>
                <div class="materi">
                    <div class="judul"><?php echo htmlspecialchars($materi['judul']); ?></div>
                    <div class="konten"><?php echo nl2br(htmlspecialchars($materi['konten'])); ?></div>
                    <?php if ($materi['file_name']): ?>
                        <a class="file-link" href="uploads/<?php echo urlencode($materi['file_name']); ?>" target="_blank">Download File</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html> 