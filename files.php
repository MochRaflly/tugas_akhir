<?php
echo "<h2>📁 Daftar File Project</h2>";

// Fungsi untuk mendapatkan ukuran file yang readable
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

// Fungsi untuk mendapatkan ekstensi file
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// Fungsi untuk mendapatkan icon berdasarkan ekstensi
function getFileIcon($extension) {
    $icons = [
        'php' => '🐘',
        'css' => '🎨',
        'html' => '🌐',
        'js' => '⚡',
        'sql' => '🗄️',
        'txt' => '📄',
        'md' => '📝',
        'jpg' => '🖼️',
        'jpeg' => '🖼️',
        'png' => '🖼️',
        'gif' => '🖼️',
        'pdf' => '📕',
        'doc' => '📘',
        'docx' => '📘',
        'zip' => '📦',
        'rar' => '📦'
    ];
    
    return $icons[$extension] ?? '📄';
}

// Daftar file yang akan ditampilkan
$files = [
    'PHP Files' => glob('*.php'),
    'CSS Files' => glob('*.css'),
    'SQL Files' => glob('*.sql'),
    'Documentation' => glob('*.md'),
    'Backup Files' => glob('backup_*.sql'),
    'Upload Files' => glob('uploads/*')
];

foreach ($files as $category => $file_list) {
    if (!empty($file_list)) {
        echo "<div class='panel'>";
        echo "<h3>$category</h3>";
        echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
        echo "<thead><tr>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>File</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: center;'>Ukuran</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: center;'>Tanggal Modifikasi</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: center;'>Aksi</th>";
        echo "</tr></thead>";
        echo "<tbody>";
        
        foreach ($file_list as $file) {
            if (file_exists($file)) {
                $extension = getFileExtension($file);
                $icon = getFileIcon($extension);
                $size = filesize($file);
                $modified = date('Y-m-d H:i:s', filemtime($file));
                
                echo "<tr>";
                echo "<td style='border: 1px solid #ddd; padding: 10px;'>$icon " . htmlspecialchars($file) . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>" . formatBytes($size) . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>$modified</td>";
                echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>";
                
                if ($extension == 'php' && $file != 'files.php') {
                    echo "<a href='$file' style='background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9em; margin-right: 5px;'>👁️ Lihat</a>";
                } elseif ($extension == 'sql' && strpos($file, 'backup_') === 0) {
                    echo "<a href='$file' download style='background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9em;'>📥 Download</a>";
                } elseif ($extension == 'css' || $extension == 'md') {
                    echo "<a href='$file' style='background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9em;'>👁️ Lihat</a>";
                } else {
                    echo "<span style='color: #6c757d;'>-</span>";
                }
                
                echo "</td>";
                echo "</tr>";
            }
        }
        
        echo "</tbody></table>";
        echo "</div>";
    }
}

// Statistik folder
echo "<div class='panel'>";
echo "<h3>📊 Statistik Project</h3>";

$total_files = 0;
$total_size = 0;
$php_files = 0;
$css_files = 0;
$sql_files = 0;

foreach (glob('*') as $file) {
    if (is_file($file)) {
        $total_files++;
        $total_size += filesize($file);
        $extension = getFileExtension($file);
        
        if ($extension == 'php') $php_files++;
        elseif ($extension == 'css') $css_files++;
        elseif ($extension == 'sql') $sql_files++;
    }
}

echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;'>";
echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;'>";
echo "<h3>$total_files</h3>";
echo "<p>Total File</p>";
echo "</div>";

echo "<div style='background: #f3e5f5; padding: 20px; border-radius: 8px; text-align: center;'>";
echo "<h3>" . formatBytes($total_size) . "</h3>";
echo "<p>Total Ukuran</p>";
echo "</div>";

echo "<div style='background: #e8f5e8; padding: 20px; border-radius: 8px; text-align: center;'>";
echo "<h3>$php_files</h3>";
echo "<p>File PHP</p>";
echo "</div>";

echo "<div style='background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center;'>";
echo "<h3>$css_files</h3>";
echo "<p>File CSS</p>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div style='text-align: center; margin-top: 30px;'>";
echo "<a href='welcome.php' class='btn btn-success' style='margin-right: 10px;'>🏠 Kembali ke Welcome</a>";
echo "<a href='info.php' class='btn btn-info' style='margin-right: 10px;'>ℹ️ Informasi Sistem</a>";
echo "<a href='status.php' class='btn btn-warning'>📊 Status Sistem</a>";
echo "</div>";
?> 