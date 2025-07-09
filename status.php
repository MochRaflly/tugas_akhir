<?php
require_once 'koneksi.php';

echo "<h2>🔍 Status Sistem</h2>";

// Cek koneksi database
echo "<h3>📊 Status Database</h3>";
try {
    $result = $conn->query("SELECT 1");
    echo "✅ Koneksi database: <span style='color: green;'>BERHASIL</span><br>";
    
    // Cek tabel yang diperlukan
    $tables = ['users', 'mata_pelajaran', 'materi', 'tugas', 'pengumpulan'];
    $missing_tables = [];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "✅ Semua tabel: <span style='color: green;'>TERSEDIA</span><br>";
    } else {
        echo "❌ Tabel yang hilang: <span style='color: red;'>" . implode(', ', $missing_tables) . "</span><br>";
    }
    
    // Cek data users
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $user_count = $result->fetch_assoc()['count'];
    echo "👥 Jumlah user: <strong>$user_count</strong><br>";
    
    // Cek data mata pelajaran
    $result = $conn->query("SELECT COUNT(*) as count FROM mata_pelajaran");
    $mapel_count = $result->fetch_assoc()['count'];
    echo "📚 Jumlah mata pelajaran: <strong>$mapel_count</strong><br>";
    
    // Cek data tugas
    $result = $conn->query("SELECT COUNT(*) as count FROM tugas");
    $tugas_count = $result->fetch_assoc()['count'];
    echo "📝 Jumlah tugas: <strong>$tugas_count</strong><br>";
    
} catch (Exception $e) {
    echo "❌ Koneksi database: <span style='color: red;'>GAGAL</span><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

// Cek folder uploads
echo "<h3>📁 Status Folder</h3>";
if (is_dir('uploads') && is_writable('uploads')) {
    echo "✅ Folder uploads: <span style='color: green;'>TERSEDIA & DAPAT DITULIS</span><br>";
} else {
    echo "❌ Folder uploads: <span style='color: red;'>TIDAK TERSEDIA ATAU TIDAK DAPAT DITULIS</span><br>";
}

// Cek file penting
echo "<h3>📄 Status File</h3>";
$important_files = ['koneksi.php', 'style.css', 'login.php', 'dashboard_guru.php', 'dashboard_siswa.php'];
foreach ($important_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file: <span style='color: green;'>TERSEDIA</span><br>";
    } else {
        echo "❌ $file: <span style='color: red;'>TIDAK TERSEDIA</span><br>";
    }
}

// Cek PHP version
echo "<h3>🐘 Status PHP</h3>";
echo "Versi PHP: <strong>" . PHP_VERSION . "</strong><br>";
echo "Ekstensi MySQL: " . (extension_loaded('mysqli') ? "✅ TERSEDIA" : "❌ TIDAK TERSEDIA") . "<br>";

echo "<h3>🔧 Aksi</h3>";
echo "<p><a href='setup.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔧 Setup Database</a>";
echo "<a href='welcome.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Kembali ke Welcome</a></p>";
?> 