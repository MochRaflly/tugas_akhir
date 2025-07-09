<?php
require_once 'koneksi.php';

echo "<h2>Pengecekan Database</h2>";

try {
    // Cek struktur tabel mata_pelajaran
    echo "<h3>Tabel mata_pelajaran:</h3>";
    $result = $koneksi->query("DESCRIBE mata_pelajaran");
    while ($row = $result->fetch_assoc()) {
        echo "<p>Kolom: {$row['Field']} - Tipe: {$row['Type']}</p>";
    }
    
    echo "<h3>Tabel users:</h3>";
    $result = $koneksi->query("DESCRIBE users");
    while ($row = $result->fetch_assoc()) {
        echo "<p>Kolom: {$row['Field']} - Tipe: {$row['Type']}</p>";
    }
    
    echo "<h3>Tabel tugas:</h3>";
    $result = $koneksi->query("DESCRIBE tugas");
    while ($row = $result->fetch_assoc()) {
        echo "<p>Kolom: {$row['Field']} - Tipe: {$row['Type']}</p>";
    }
    
    echo "<h3>Tabel pengumpulan:</h3>";
    $result = $koneksi->query("DESCRIBE pengumpulan");
    while ($row = $result->fetch_assoc()) {
        echo "<p>Kolom: {$row['Field']} - Tipe: {$row['Type']}</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 