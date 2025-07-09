<?php
require_once 'koneksi.php';

echo "<h2>💾 Backup Database</h2>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_backup'])) {
    try {
        // Buat nama file backup
        $backup_file = 'backup_sekolah_db_' . date('Y-m-d_H-i-s') . '.sql';
        
        // Ambil semua tabel
        $tables = [];
        $result = $conn->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        
        $backup_content = "-- Backup Database Sekolah\n";
        $backup_content .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Backup struktur dan data untuk setiap tabel
        foreach ($tables as $table) {
            // Backup struktur tabel
            $result = $conn->query("SHOW CREATE TABLE $table");
            $row = $result->fetch_array();
            $backup_content .= "-- Struktur tabel $table\n";
            $backup_content .= $row[1] . ";\n\n";
            
            // Backup data tabel
            $result = $conn->query("SELECT * FROM $table");
            if ($result->num_rows > 0) {
                $backup_content .= "-- Data tabel $table\n";
                while ($row = $result->fetch_assoc()) {
                    $backup_content .= "INSERT INTO $table VALUES (";
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . $conn->real_escape_string($value) . "'";
                        }
                    }
                    $backup_content .= implode(', ', $values) . ");\n";
                }
                $backup_content .= "\n";
            }
        }
        
        // Simpan file backup
        if (file_put_contents($backup_file, $backup_content)) {
            echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin: 20px 0;'>";
            echo "<h3>✅ Backup berhasil dibuat!</h3>";
            echo "<p><strong>File:</strong> $backup_file</p>";
            echo "<p><strong>Ukuran:</strong> " . number_format(filesize($backup_file)) . " bytes</p>";
            echo "<p><a href='$backup_file' download style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📥 Download Backup</a></p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin: 20px 0;'>";
            echo "<h3>❌ Gagal membuat backup!</h3>";
            echo "<p>Pastikan folder dapat ditulis.</p>";
            echo "</div>";
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin: 20px 0;'>";
        echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
        echo "</div>";
    }
} else {
    echo "<div style='background: #e2e3e5; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>📋 Informasi Backup</h3>";
    echo "<p>Backup akan menyimpan:</p>";
    echo "<ul>";
    echo "<li>Struktur semua tabel</li>";
    echo "<li>Semua data dalam database</li>";
    echo "<li>File dalam format .sql</li>";
    echo "</ul>";
    echo "<p>File backup akan disimpan di folder yang sama dengan aplikasi.</p>";
    echo "</div>";
    
    echo "<form method='POST' style='text-align: center;'>";
    echo "<input type='hidden' name='create_backup' value='1'>";
    echo "<button type='submit' style='background: #17a2b8; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 1.1em; margin-right: 10px;'>💾 Buat Backup</button>";
    echo "<a href='welcome.php' style='background: #6c757d; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 1.1em;'>🏠 Kembali</a>";
    echo "</form>";
}

// Tampilkan daftar file backup yang ada
$backup_files = glob('backup_sekolah_db_*.sql');
if (!empty($backup_files)) {
    echo "<h3>📁 File Backup yang Tersedia</h3>";
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<thead><tr><th style='border: 1px solid #ddd; padding: 10px;'>File</th><th style='border: 1px solid #ddd; padding: 10px;'>Ukuran</th><th style='border: 1px solid #ddd; padding: 10px;'>Tanggal</th><th style='border: 1px solid #ddd; padding: 10px;'>Aksi</th></tr></thead>";
    echo "<tbody>";
    foreach ($backup_files as $file) {
        $size = filesize($file);
        $date = date('Y-m-d H:i:s', filemtime($file));
        echo "<tr>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>$file</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . number_format($size) . " bytes</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>$date</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>";
        echo "<a href='$file' download style='background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9em;'>📥 Download</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}
?> 