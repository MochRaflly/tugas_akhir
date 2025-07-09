<?php
require_once 'koneksi.php';

echo "<h2>🔧 Perbaiki Kolom Nama</h2>";

try {
    // Perbaiki tabel mata_pelajaran
    $conn->query("ALTER TABLE mata_pelajaran CHANGE nama_mapel nama VARCHAR(100) NOT NULL");
    echo "✅ Berhasil mengubah nama_mapel menjadi nama di tabel mata_pelajaran<br>";
    
    // Perbaiki tabel users
    $conn->query("ALTER TABLE users CHANGE nama_lengkap nama VARCHAR(100) NOT NULL");
    echo "✅ Berhasil mengubah nama_lengkap menjadi nama di tabel users<br>";
    
    echo "<h3>🎉 Selesai!</h3>";
    echo "<p>Kolom nama sudah diperbaiki. Coba akses halaman lagi.</p>";
    
    echo "<a href='dashboard_siswa.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Dashboard Siswa</a>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
}
?> 