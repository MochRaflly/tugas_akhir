<?php
require_once 'koneksi.php';

echo "<h2>🔧 Memperbaiki Struktur Database</h2>";

try {
    // Perbaiki tabel mata_pelajaran
    echo "<p>📚 Memperbaiki tabel mata_pelajaran...</p>";
    $conn->query("ALTER TABLE mata_pelajaran CHANGE nama_mapel nama VARCHAR(100) NOT NULL");
    echo "✅ Berhasil mengubah nama_mapel menjadi nama<br>";
    
    // Perbaiki tabel users
    echo "<p>👥 Memperbaiki tabel users...</p>";
    $conn->query("ALTER TABLE users CHANGE nama_lengkap nama VARCHAR(100) NOT NULL");
    echo "✅ Berhasil mengubah nama_lengkap menjadi nama<br>";
    
    // Tambahkan kolom email jika belum ada
    echo "<p>📧 Menambahkan kolom email...</p>";
    $conn->query("ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL");
    echo "✅ Berhasil menambahkan kolom email<br>";
    
    // Tambahkan kolom created_at jika belum ada
    echo "<p>⏰ Menambahkan kolom created_at...</p>";
    $conn->query("ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "✅ Berhasil menambahkan kolom created_at<br>";
    
    // Perbaiki tabel pengumpulan
    echo "<p>📤 Memperbaiki tabel pengumpulan...</p>";
    $conn->query("ALTER TABLE pengumpulan CHANGE tanggal_kirim created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "✅ Berhasil mengubah tanggal_kirim menjadi created_at<br>";
    
    $conn->query("ALTER TABLE pengumpulan ADD COLUMN komentar TEXT NULL");
    echo "✅ Berhasil menambahkan kolom komentar<br>";
    
    $conn->query("ALTER TABLE pengumpulan ADD COLUMN komentar_guru TEXT NULL");
    echo "✅ Berhasil menambahkan kolom komentar_guru<br>";
    
    echo "<h3>🎉 Perbaikan database selesai!</h3>";
    echo "<p><a href='login.php'>Kembali ke Login</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<p>Silakan periksa struktur database Anda.</p>";
}
?> 