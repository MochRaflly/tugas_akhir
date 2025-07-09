<?php
require_once 'koneksi.php';

echo "<h2>🔄 Reset Database</h2>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_reset'])) {
    try {
        echo "<p>🗑️ Menghapus semua tabel...</p>";
        
        // Drop semua tabel
        $conn->query("DROP TABLE IF EXISTS pengumpulan");
        echo "✅ Tabel pengumpulan dihapus<br>";
        
        $conn->query("DROP TABLE IF EXISTS tugas");
        echo "✅ Tabel tugas dihapus<br>";
        
        $conn->query("DROP TABLE IF EXISTS materi");
        echo "✅ Tabel materi dihapus<br>";
        
        $conn->query("DROP TABLE IF EXISTS mata_pelajaran");
        echo "✅ Tabel mata_pelajaran dihapus<br>";
        
        $conn->query("DROP TABLE IF EXISTS users");
        echo "✅ Tabel users dihapus<br>";
        
        echo "<h3>🎉 Database berhasil direset!</h3>";
        echo "<p>Silakan jalankan <a href='setup.php'>setup.php</a> untuk membuat ulang database.</p>";
        
    } catch (Exception $e) {
        echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    }
} else {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin: 20px 0;'>";
    echo "<h3>⚠️ PERHATIAN!</h3>";
    echo "<p><strong>Reset database akan menghapus SEMUA data yang ada!</strong></p>";
    echo "<p>Ini termasuk:</p>";
    echo "<ul>";
    echo "<li>Semua user (guru dan siswa)</li>";
    echo "<li>Semua mata pelajaran</li>";
    echo "<li>Semua materi pembelajaran</li>";
    echo "<li>Semua tugas</li>";
    echo "<li>Semua pengumpulan tugas</li>";
    echo "</ul>";
    echo "<p>Data yang dihapus TIDAK DAPAT DIPULIHKAN!</p>";
    echo "</div>";
    
    echo "<form method='POST' style='text-align: center;'>";
    echo "<input type='hidden' name='confirm_reset' value='1'>";
    echo "<button type='submit' style='background: #dc3545; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 1.1em; margin-right: 10px;'>🗑️ YA, RESET DATABASE</button>";
    echo "<a href='welcome.php' style='background: #6c757d; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 1.1em;'>❌ BATAL</a>";
    echo "</form>";
}
?> 