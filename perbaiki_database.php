<?php
require_once 'koneksi.php';

echo "<h2>Perbaikan Database</h2>";

try {
    // Cek apakah kolom nama_mapel ada
    $result = $koneksi->query("SHOW COLUMNS FROM mata_pelajaran LIKE 'nama_mapel'");
    if ($result->num_rows > 0) {
        // Rename kolom dari nama_mapel ke nama
        $koneksi->query("ALTER TABLE mata_pelajaran CHANGE nama_mapel nama VARCHAR(100) NOT NULL");
        echo "<p style='color: green;'>✓ Kolom nama_mapel berhasil diubah menjadi nama</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Kolom nama sudah ada</p>";
    }
    
    echo "<p><a href='index.php'>Kembali ke Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 