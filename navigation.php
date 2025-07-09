<?php
if (!isset($_SESSION)) session_start();
$role = $_SESSION['role'] ?? null;
?>
<div class="nav">
<?php if ($role === 'guru'): ?>
    <a href="dashboard_guru.php">Dashboard</a>
    <a href="profil.php">Profil</a>
    <a href="kelola_mapel.php">Kelola Mapel</a>
    <a href="kelola_materi.php">Kelola Materi</a>
    <a href="buat_tugas.php">Buat Tugas</a>
    <a href="kelola_tugas.php">Kelola Tugas</a>
    <a href="laporan_nilai.php">Laporan Nilai</a>
    <a href="logout.php">Logout</a>
<?php elseif ($role === 'siswa'): ?>
    <a href="dashboard_siswa.php">Dashboard</a>
    <a href="profil.php">Profil</a>
    <a href="daftar_mapel.php">Daftar Mapel</a>
    <a href="daftar_tugas.php">Daftar Tugas</a>
    <a href="riwayat_tugas.php">Riwayat Tugas</a>
    <a href="nilai_tugas.php">Nilai Tugas</a>
    <a href="logout.php">Logout</a>
<?php endif; ?>
</div> 