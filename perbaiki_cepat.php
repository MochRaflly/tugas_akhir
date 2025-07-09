<?php
require_once 'koneksi.php';

echo "<h2>🔧 Perbaikan Cepat Database</h2>";
echo "<p>Memperbaiki kolom nama di tabel mata_pelajaran...</p>";

try {
    // Cek apakah kolom nama_mapel ada
    $result = $conn->query("SHOW COLUMNS FROM mata_pelajaran LIKE 'nama_mapel'");
    if ($result->num_rows > 0) {
        echo "<p>📚 Mengubah nama_mapel menjadi nama...</p>";
        $conn->query("ALTER TABLE mata_pelajaran CHANGE nama_mapel nama VARCHAR(100) NOT NULL");
        echo "✅ Berhasil mengubah nama_mapel menjadi nama<br>";
    } else {
        echo "✅ Kolom nama sudah ada<br>";
    }
    
    // Cek apakah kolom nama_lengkap ada di users
    $result = $conn->query("SHOW COLUMNS FROM users LIKE 'nama_lengkap'");
    if ($result->num_rows > 0) {
        echo "<p>👥 Mengubah nama_lengkap menjadi nama...</p>";
        $conn->query("ALTER TABLE users CHANGE nama_lengkap nama VARCHAR(100) NOT NULL");
        echo "✅ Berhasil mengubah nama_lengkap menjadi nama<br>";
    } else {
        echo "✅ Kolom nama sudah ada di users<br>";
    }
    
    echo "<h3>🎉 Perbaikan selesai!</h3>";
    echo "<p>Database sudah diperbaiki. Silakan coba akses halaman lagi.</p>";
    
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='dashboard_siswa.php' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🏠 Dashboard Siswa</a>";
    echo "<a href='daftar_tugas.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📋 Daftar Tugas</a>";
    echo "<a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Login</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<p>Silakan coba jalankan <a href='setup.php'>setup.php</a> untuk membuat ulang database.</p>";
}
?> 