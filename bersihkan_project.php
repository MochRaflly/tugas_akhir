<?php
echo "<h2>🧹 Membersihkan Project</h2>";
echo "<p>Menghapus file-file yang tidak diperlukan...</p>";

// Daftar file yang akan dihapus
$files_to_delete = [
    'fix_database.sql',
    'database_fix.sql', 
    'database.sql',
    'tugas_akhir.sql',
    'error.php',
    'footer.php',
    'header.php',
    'navigation.php',
    'download.php',
    'riwayat_tugas.php',
    'laporan_nilai.php',
    'beri_nilai.php',
    'lihat_pengumpulan.php',
    'kelola_tugas.php',
    'buat_tugas.php',
    'kelola_materi.php',
    'tambah_materi.php',
    'kelola_mapel.php',
    'nilai_tugas.php',
    'upload_tugas.php',
    'lihat_materi.php',
    'cek_koneksi.php'
];

$deleted_count = 0;
$error_count = 0;

foreach ($files_to_delete as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "✅ Dihapus: $file<br>";
            $deleted_count++;
        } else {
            echo "❌ Gagal menghapus: $file<br>";
            $error_count++;
        }
    } else {
        echo "ℹ️ Tidak ada: $file<br>";
    }
}

echo "<h3>📊 Hasil Pembersihan</h3>";
echo "<p>✅ Berhasil dihapus: $deleted_count file</p>";
echo "<p>❌ Gagal dihapus: $error_count file</p>";

echo "<h3>🎉 Project sudah dibersihkan!</h3>";
echo "<p>File-file yang tidak diperlukan sudah dihapus.</p>";

echo "<div style='margin: 20px 0;'>";
echo "<a href='perbaiki_cepat.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔧 Perbaiki Database</a>";
echo "<a href='welcome.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Kembali ke Welcome</a>";
echo "</div>";
?> 