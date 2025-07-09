<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: login.php');
    exit;
}

$guru_id = $_SESSION['user_id'];
$error = '';

// Ambil mapel milik guru
$mapel_result = mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE guru_id = $guru_id");
$mapel_id = isset($_GET['mapel_id']) ? intval($_GET['mapel_id']) : 0;
$mapel_data = null;
if ($mapel_id) {
    $mapel_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mata_pelajaran WHERE id=$mapel_id AND guru_id=$guru_id"));
    if (!$mapel_data) {
        $error = 'Mata pelajaran tidak valid.';
    }
}

$nilai_result = null;
if ($mapel_data) {
    // Ambil semua tugas di mapel ini
    $tugas_result = mysqli_query($conn, "SELECT id, judul FROM tugas WHERE mapel_id = $mapel_id ORDER BY id");
    $tugas_list = [];
    while ($t = mysqli_fetch_assoc($tugas_result)) {
        $tugas_list[] = $t;
    }
    // Ambil semua siswa yang pernah mengumpulkan tugas di mapel ini
    $siswa_result = mysqli_query($conn, "
        SELECT DISTINCT u.id, u.nama_lengkap
        FROM pengumpulan p
        JOIN tugas t ON p.tugas_id = t.id
        JOIN users u ON p.siswa_id = u.id
        WHERE t.mapel_id = $mapel_id
        ORDER BY u.nama_lengkap
    ");
    $siswa_list = [];
    while ($s = mysqli_fetch_assoc($siswa_result)) {
        $siswa_list[] = $s;
    }
    // Ambil semua nilai
    $nilai_result = [];
    foreach ($siswa_list as $siswa) {
        $row = ['nama_lengkap' => $siswa['nama_lengkap']];
        foreach ($tugas_list as $tugas) {
            $nilaiq = mysqli_query($conn, "SELECT nilai FROM pengumpulan WHERE tugas_id = {$tugas['id']} AND siswa_id = {$siswa['id']}");
            $nilai = mysqli_fetch_assoc($nilaiq);
            $row[$tugas['id']] = $nilai ? $nilai['nilai'] : '-';
        }
        $nilai_result[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 95%; max-width: 1100px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { margin-top: 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .nav a:hover { text-decoration: underline; }
        .error { color: red; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #f0f0f0; }
        td:first-child, th:first-child { text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard_guru.php">Dashboard</a>
            <a href="kelola_mapel.php">Kelola Mapel</a>
            <a href="logout.php">Logout</a>
        </div>
        <h2>Laporan Nilai Siswa</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="get" style="margin-bottom:20px;">
            <label for="mapel_id">Pilih Mata Pelajaran:</label>
            <select name="mapel_id" id="mapel_id" required onchange="this.form.submit()">
                <option value="">-- Pilih Mapel --</option>
                <?php while($m = mysqli_fetch_assoc($mapel_result)): ?>
                    <option value="<?php echo $m['id']; ?>" <?php if($mapel_id==$m['id']) echo 'selected'; ?>><?php echo htmlspecialchars($m['nama_mapel']); ?></option>
                <?php endwhile; ?>
            </select>
        </form>
        <?php if ($mapel_data && $nilai_result !== null): ?>
            <h3>Mata Pelajaran: <?php echo htmlspecialchars($mapel_data['nama_mapel']); ?></h3>
            <table>
                <tr>
                    <th>Nama Siswa</th>
                    <?php foreach ($tugas_list as $tugas): ?>
                        <th><?php echo htmlspecialchars($tugas['judul']); ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($nilai_result as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                    <?php foreach ($tugas_list as $tugas): ?>
                        <td><?php echo htmlspecialchars($row[$tugas['id']]); ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif($mapel_id): ?>
            <p>Tidak ada data nilai untuk mata pelajaran ini.</p>
        <?php endif; ?>
    </div>
</body>
</html> 