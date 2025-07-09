<?php
echo "<h2>📋 Log Error PHP</h2>";

// Cek apakah ada error log
$error_log_paths = [
    'error_log',
    'php_errors.log',
    'error.log',
    ini_get('error_log')
];

$found_logs = [];

foreach ($error_log_paths as $log_path) {
    if ($log_path && file_exists($log_path)) {
        $found_logs[] = $log_path;
    }
}

if (empty($found_logs)) {
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin: 20px 0;'>";
    echo "<h3>✅ Tidak ada error log yang ditemukan</h3>";
    echo "<p>Sistem berjalan dengan baik tanpa error.</p>";
    echo "</div>";
} else {
    foreach ($found_logs as $log_file) {
        echo "<div class='panel'>";
        echo "<h3>📄 Log File: $log_file</h3>";
        
        $log_content = file_get_contents($log_file);
        if ($log_content) {
            // Ambil 50 baris terakhir
            $lines = explode("\n", $log_content);
            $recent_lines = array_slice($lines, -50);
            
            echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 0.9em;'>";
            foreach ($recent_lines as $line) {
                if (trim($line)) {
                    // Highlight error lines
                    if (strpos($line, 'ERROR') !== false || strpos($line, 'Fatal') !== false) {
                        echo "<div style='color: #dc3545; margin: 2px 0;'>" . htmlspecialchars($line) . "</div>";
                    } elseif (strpos($line, 'Warning') !== false) {
                        echo "<div style='color: #ffc107; margin: 2px 0;'>" . htmlspecialchars($line) . "</div>";
                    } else {
                        echo "<div style='color: #6c757d; margin: 2px 0;'>" . htmlspecialchars($line) . "</div>";
                    }
                }
            }
            echo "</div>";
            
            echo "<p style='margin-top: 10px;'><strong>Total baris:</strong> " . count($lines) . " (menampilkan 50 baris terakhir)</p>";
        } else {
            echo "<p>File log kosong atau tidak dapat dibaca.</p>";
        }
        echo "</div>";
    }
}

// Cek konfigurasi error reporting
echo "<div class='panel'>";
echo "<h3>⚙️ Konfigurasi Error Reporting</h3>";

$error_reporting = error_reporting();
$display_errors = ini_get('display_errors');
$log_errors = ini_get('log_errors');
$error_log = ini_get('error_log');

echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
echo "<thead><tr>";
echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Setting</th>";
echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Value</th>";
echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: center;'>Status</th>";
echo "</tr></thead>";
echo "<tbody>";

echo "<tr>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>Error Reporting</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>$error_reporting</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>";
echo $error_reporting > 0 ? "✅ Aktif" : "❌ Nonaktif";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>Display Errors</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>$display_errors</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>";
echo $display_errors ? "✅ Aktif" : "❌ Nonaktif";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>Log Errors</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>$log_errors</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>";
echo $log_errors ? "✅ Aktif" : "❌ Nonaktif";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>Error Log File</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . ($error_log ?: 'Default') . "</td>";
echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>";
echo $error_log && file_exists($error_log) ? "✅ Tersedia" : "❌ Tidak tersedia";
echo "</td>";
echo "</tr>";

echo "</tbody></table>";
echo "</div>";

// Tips untuk debugging
echo "<div class='panel'>";
echo "<h3>🔧 Tips Debugging</h3>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
echo "<h4>Jika ada error:</h4>";
echo "<ol>";
echo "<li>Periksa log error di atas</li>";
echo "<li>Pastikan semua file PHP ada dan dapat diakses</li>";
echo "<li>Periksa permission folder dan file</li>";
echo "<li>Pastikan database terkonfigurasi dengan benar</li>";
echo "<li>Jalankan <a href='status.php'>status.php</a> untuk cek sistem</li>";
echo "<li>Jalankan <a href='setup.php'>setup.php</a> jika ada masalah database</li>";
echo "</ol>";
echo "</div>";
echo "</div>";

echo "<div style='text-align: center; margin-top: 30px;'>";
echo "<a href='welcome.php' class='btn btn-success' style='margin-right: 10px;'>🏠 Kembali ke Welcome</a>";
echo "<a href='status.php' class='btn btn-info' style='margin-right: 10px;'>📊 Status Sistem</a>";
echo "<a href='info.php' class='btn btn-warning'>ℹ️ Informasi Sistem</a>";
echo "</div>";
?> 