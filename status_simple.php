<?php
require_once 'koneksi.php';

echo "<h2>📊 Status Sistem</h2>";

// Cek koneksi database
try {
    $result = $conn->query("SELECT 1");
    echo "✅ Database: <span style='color: green;'>TERHUBUNG</span><br>";
    
    // Cek tabel utama
    $tables = ['users', 'mata_pelajaran', 'materi', 'tugas', 'pengumpulan'];
    $missing = [];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            $missing[] = $table;
        }
    }
    
    if (empty($missing)) {
        echo "✅ Tabel: <span style='color: green;'>LENGKAP</span><br>";
    } else {
        echo "❌ Tabel yang hilang: " . implode(', ', $missing) . "<br>";
    }
    
    // Cek kolom nama di mata_pelajaran
    $result = $conn->query("SHOW COLUMNS FROM mata_pelajaran LIKE 'nama'");
    if ($result->num_rows > 0) {
        echo "✅ Kolom nama: <span style='color: green;'>ADA</span><br>";
    } else {
        echo "❌ Kolom nama: <span style='color: red;'>TIDAK ADA</span><br>";
    }
    
    // Cek data
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $user_count = $result->fetch_assoc()['count'];
    echo "👥 User: <strong>$user_count</strong><br>";
    
} catch (Exception $e) {
    echo "❌ Database: <span style='color: red;'>GAGAL</span><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

// Cek file penting
$important_files = ['koneksi.php', 'style.css', 'login.php', 'dashboard_guru.php', 'dashboard_siswa.php'];
$missing_files = [];

foreach ($important_files as $file) {
    if (!file_exists($file)) {
        $missing_files[] = $file;
    }
}

if (empty($missing_files)) {
    echo "✅ File: <span style='color: green;'>LENGKAP</span><br>";
} else {
    echo "❌ File yang hilang: " . implode(', ', $missing_files) . "<br>";
}

// Cek folder uploads
if (is_dir('uploads') && is_writable('uploads')) {
    echo "✅ Upload folder: <span style='color: green;'>OK</span><br>";
} else {
    echo "❌ Upload folder: <span style='color: red;'>MASALAH</span><br>";
}

echo "<h3>🔧 Aksi</h3>";
echo "<div style='margin: 20px 0;'>";
echo "<a href='perbaiki_cepat.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔧 Perbaiki Database</a>";
echo "<a href='bersihkan_project.php' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🧹 Bersihkan Project</a>";
echo "<a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Login</a>";
echo "</div>";
?> 