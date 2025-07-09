<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Sistem - Sistem Manajemen Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>ℹ️ Informasi Sistem</h2>
        
        <div class="panel">
            <h3>🏫 Tentang Sistem</h3>
            <p>Sistem Manajemen Sekolah adalah aplikasi web berbasis PHP yang dirancang untuk memudahkan proses pembelajaran antara guru dan siswa. Sistem ini menyediakan fitur-fitur lengkap untuk manajemen mata pelajaran, materi pembelajaran, tugas, dan penilaian.</p>
        </div>
        
        <div class="panel">
            <h3>🛠️ Teknologi yang Digunakan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <h4>Backend</h4>
                    <ul>
                        <li>PHP 7.4+</li>
                        <li>MySQL/MariaDB</li>
                        <li>Session Management</li>
                        <li>File Upload</li>
                    </ul>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <h4>Frontend</h4>
                    <ul>
                        <li>HTML5</li>
                        <li>CSS3 (Custom)</li>
                        <li>JavaScript</li>
                        <li>Responsive Design</li>
                    </ul>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <h4>Server</h4>
                    <ul>
                        <li>PHP Built-in Server</li>
                        <li>XAMPP/MariaDB</li>
                        <li>Apache (Opsional)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="panel">
            <h3>👥 Fitur Per Role</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h4>👨‍🏫 Guru</h4>
                    <ul>
                        <li><strong>Dashboard Guru:</strong> Statistik dan ringkasan aktivitas mengajar</li>
                        <li><strong>Kelola Mata Pelajaran:</strong> Tambah, edit, dan hapus mata pelajaran</li>
                        <li><strong>Kelola Materi:</strong> Upload dan kelola materi pembelajaran</li>
                        <li><strong>Buat Tugas:</strong> Buat tugas dengan deadline dan deskripsi</li>
                        <li><strong>Laporan Nilai:</strong> Lihat dan beri nilai tugas siswa</li>
                        <li><strong>Profil:</strong> Edit informasi profil dan password</li>
                    </ul>
                </div>
                <div>
                    <h4>👨‍🎓 Siswa</h4>
                    <ul>
                        <li><strong>Dashboard Siswa:</strong> Statistik tugas dan nilai</li>
                        <li><strong>Daftar Tugas:</strong> Lihat semua tugas yang tersedia</li>
                        <li><strong>Kerjakan Tugas:</strong> Upload file tugas dan komentar</li>
                        <li><strong>Daftar Mata Pelajaran:</strong> Lihat mata pelajaran yang tersedia</li>
                        <li><strong>Daftar Materi:</strong> Download materi pembelajaran</li>
                        <li><strong>Nilai Saya:</strong> Lihat nilai tugas yang sudah dinilai</li>
                        <li><strong>Profil:</strong> Edit informasi profil dan password</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="panel">
            <h3>🔒 Keamanan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <h4>✅ Implemented</h4>
                    <ul>
                        <li>Password Hashing (bcrypt)</li>
                        <li>Prepared Statements</li>
                        <li>Session Management</li>
                        <li>Input Validation</li>
                        <li>File Upload Security</li>
                    </ul>
                </div>
                <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <h4>⚠️ Best Practices</h4>
                    <ul>
                        <li>HTTPS (Production)</li>
                        <li>Rate Limiting</li>
                        <li>CSRF Protection</li>
                        <li>Logging & Monitoring</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="panel">
            <h3>📊 Statistik Sistem</h3>
            <?php
            require_once 'koneksi.php';
            try {
                $result = $conn->query("SELECT COUNT(*) as count FROM users");
                $user_count = $result->fetch_assoc()['count'];
                
                $result = $conn->query("SELECT COUNT(*) as count FROM mata_pelajaran");
                $mapel_count = $result->fetch_assoc()['count'];
                
                $result = $conn->query("SELECT COUNT(*) as count FROM tugas");
                $tugas_count = $result->fetch_assoc()['count'];
                
                $result = $conn->query("SELECT COUNT(*) as count FROM pengumpulan");
                $pengumpulan_count = $result->fetch_assoc()['count'];
                
                echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;'>";
                echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;'>";
                echo "<h3>$user_count</h3>";
                echo "<p>Total User</p>";
                echo "</div>";
                
                echo "<div style='background: #f3e5f5; padding: 20px; border-radius: 8px; text-align: center;'>";
                echo "<h3>$mapel_count</h3>";
                echo "<p>Mata Pelajaran</p>";
                echo "</div>";
                
                echo "<div style='background: #e8f5e8; padding: 20px; border-radius: 8px; text-align: center;'>";
                echo "<h3>$tugas_count</h3>";
                echo "<p>Total Tugas</p>";
                echo "</div>";
                
                echo "<div style='background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center;'>";
                echo "<h3>$pengumpulan_count</h3>";
                echo "<p>Pengumpulan Tugas</p>";
                echo "</div>";
                echo "</div>";
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>Tidak dapat mengambil statistik database.</p>";
            }
            ?>
        </div>
        
        <div class="panel">
            <h3>📞 Support & Troubleshooting</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h4>Jika ada masalah:</h4>
                <ol>
                    <li><strong>Error Database:</strong> Jalankan <a href="setup.php">setup.php</a> atau <a href="fix_db.php">fix_db.php</a></li>
                    <li><strong>Error 500:</strong> Periksa log error PHP dan struktur database</li>
                    <li><strong>Login Gagal:</strong> Pastikan database terkonfigurasi dengan benar</li>
                    <li><strong>File Upload:</strong> Periksa permission folder uploads/</li>
                    <li><strong>Tampilan:</strong> Pastikan file style.css terload dengan benar</li>
                    <li><strong>Reset Total:</strong> Jalankan <a href="reset_db.php">reset_db.php</a> jika perlu</li>
                </ol>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="welcome.php" class="btn btn-success" style="margin-right: 10px;">🏠 Kembali ke Welcome</a>
            <a href="status.php" class="btn btn-info" style="margin-right: 10px;">📊 Status Sistem</a>
            <a href="login.php" class="btn">🔐 Login</a>
        </div>
    </div>
</body>
</html> 